<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\User;
use App\Models\EmployeeBankDetail;
use App\Services\AuditService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('user.role', 'processor')
            ->orderBy('created_at', 'desc');

        if ($request->month) {
            $query->where('month', $request->month);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->paginate(20);
        $months = Payroll::selectRaw('DISTINCT month')->orderBy('month', 'desc')->pluck('month');

        $stats = [
            'total_this_month' => Payroll::where('month', now()->format('Y-m'))->sum('total_salary'),
            'pending_count' => Payroll::where('status', 'pending')->count(),
            'paid_count' => Payroll::where('status', 'paid')->count(),
            'employee_count' => User::whereHas('role', fn($q) => $q->whereIn('slug', ['admin', 'manager', 'receptionist']))->count(),
        ];

        return view('admin.payroll.index', compact('payrolls', 'months', 'stats'));
    }

    public function create()
    {
        $employees = User::whereHas('role', fn($q) => $q->whereIn('slug', ['admin', 'manager', 'receptionist']))
            ->with('role', 'bankDetail')
            ->get();

        $currentMonth = now()->format('Y-m');

        $existing = Payroll::where('month', $currentMonth)->pluck('user_id')->toArray();

        return view('admin.payroll.create', compact('employees', 'currentMonth', 'existing'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|date_format:Y-m',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $exists = Payroll::where('user_id', $validated['user_id'])
            ->where('month', $validated['month'])->exists();

        if ($exists) {
            return back()->withErrors(['user_id' => 'Payroll already exists for this employee this month.']);
        }

        $validated['bonus'] ??= 0;
        $validated['deductions'] ??= 0;
        $validated['total_salary'] = $validated['base_salary'] + $validated['bonus'] - $validated['deductions'];
        $validated['status'] = 'pending';

        $payroll = Payroll::create($validated);
        AuditService::payrollGenerated($payroll);

        return redirect()->route('admin.payroll.index')
            ->with('success', 'Payroll record created.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('user.role', 'user.bankDetail', 'processor');
        return view('admin.payroll.show', compact('payroll'));
    }

    public function approve(Payroll $payroll)
    {
        if ($payroll->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending payroll can be approved.']);
        }

        $payroll->update([
            'status' => 'approved',
            'processed_by' => Auth::id(),
        ]);

        AuditService::payrollApproved($payroll);
        return back()->with('success', 'Payroll approved.');
    }

    public function markPaid(Payroll $payroll)
    {
        if ($payroll->status !== 'approved') {
            return back()->withErrors(['status' => 'Only approved payroll can be marked as paid.']);
        }

        $payroll->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        AuditService::payrollPaid($payroll);
        return back()->with('success', 'Payroll marked as paid.');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->withErrors(['status' => 'Cannot delete paid payroll records.']);
        }

        $payroll->delete();
        return redirect()->route('admin.payroll.index')->with('success', 'Payroll record deleted.');
    }

    public function generateAll()
    {
        $month = now()->format('Y-m');
        $employees = User::whereHas('role', fn($q) => $q->whereIn('slug', ['admin', 'manager', 'receptionist']))
            ->with('role')
            ->get();

        $count = 0;
        foreach ($employees as $employee) {
            if (Payroll::where('user_id', $employee->id)->where('month', $month)->exists()) {
                continue;
            }

            $baseSalary = match ($employee->role?->slug) {
                'admin' => 1500000,
                'manager' => 800000,
                'receptionist' => 400000,
                default => 300000,
            };

            $payroll = Payroll::create([
                'user_id' => $employee->id,
                'month' => $month,
                'base_salary' => $baseSalary,
                'bonus' => 0,
                'deductions' => 0,
                'total_salary' => $baseSalary,
                'status' => 'pending',
            ]);

            AuditService::payrollGenerated($payroll);
            $count++;
        }

        return redirect()->route('admin.payroll.index')
            ->with('success', "Generated payroll for {$count} employees.");
    }

    public function downloadPayslip(Payroll $payroll)
    {
        $payroll->load('user.role', 'user.bankDetail', 'processor');
        $pdf = Pdf::loadView('admin.payroll.payslip', compact('payroll'));
        $filename = "payslip-{$payroll->user->name}-{$payroll->month}.pdf";
        return $pdf->download($filename);
    }

    public function employeeDetails()
    {
        $detail = EmployeeBankDetail::firstOrNew(['user_id' => Auth::id()]);
        return view('profile.bank-details', compact('detail'));
    }

    public function updateEmployeeDetails(Request $request)
    {
        $validated = $request->validate([
            'payment_type' => 'required|in:bank,mobile_money,card',
            'bank_name' => 'required_if:payment_type,bank|nullable|string|max:255',
            'account_number' => 'required_if:payment_type,bank|nullable|string|max:50',
            'account_holder_name' => 'required_if:payment_type,bank|nullable|string|max:255',
            'mobile_provider' => 'required_if:payment_type,mobile_money|nullable|string|max:50',
            'mobile_number' => 'required_if:payment_type,mobile_money|nullable|string|max:20',
            'card_last_four' => 'required_if:payment_type,card|nullable|string|size:4',
            'card_holder_name' => 'required_if:payment_type,card|nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        EmployeeBankDetail::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        AuditService::log('BANK_DETAILS_UPDATED', 'User', "Payment details updated by {$request->user()->name}");

        return back()->with('success', 'Payment details saved.');
    }
}
