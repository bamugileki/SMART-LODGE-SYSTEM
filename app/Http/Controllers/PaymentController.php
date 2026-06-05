<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->guest_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        return view('payments.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->guest_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,mobile_money,stripe,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        if ((float)$validated['amount'] !== (float)$booking->total) {
            return back()->withErrors(['amount' => 'Payment must be exactly TSh ' . number_format($booking->total, 2) . '. No partial or extra payments allowed.']);
        }

        $payment = Payment::create([
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'booking_id' => $booking->id,
            'amount' => $booking->total,
            'method' => $validated['method'],
            'status' => 'paid',
            'notes' => $validated['notes'] ?? null,
            'paid_at' => now(),
            'processed_by' => Auth::id(),
        ]);

        $booking->update(['status' => 'confirmed']);

        return redirect()->route('payments.receipt', $payment)
            ->with('success', 'Payment successful. Receipt #' . $payment->receipt_number . ' generated.');
    }

    public function receipt(Payment $payment)
    {
        $payment->load('booking.room.roomType', 'booking.guest', 'processor', 'verifier');

        if ($payment->booking->guest_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        $settings = Setting::allToArray();

        return view('payments.receipt', compact('payment', 'settings'));
    }

    public function downloadReceipt(Payment $payment)
    {
        $payment->load('booking.room.roomType', 'booking.guest', 'processor', 'verifier');

        if ($payment->booking->guest_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        $settings = Setting::allToArray();

        $pdf = Pdf::loadView('payments.receipt-pdf', compact('payment', 'settings'));
        $pdf->setPaper('A4');

        return $pdf->download("receipt-{$payment->receipt_number}.pdf");
    }

    public function verify(Payment $payment)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        if ($payment->status !== 'paid') {
            return back()->with('error', 'Only paid payments can be verified.');
        }

        $payment->update([
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', "Receipt #{$payment->receipt_number} verified successfully.");
    }

    public function unverify(Payment $payment)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment->update([
            'verified_by' => null,
            'verified_at' => null,
        ]);

        return back()->with('success', "Receipt #{$payment->receipt_number} un-verified.");
    }

    public function index()
    {
        $payments = Payment::with('booking.guest', 'booking.room', 'processor', 'verifier')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }
}
