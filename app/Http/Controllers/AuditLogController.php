<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->module) {
            $query->where('module', $request->module);
        }
        if ($request->action) {
            $query->where('action', $request->action);
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $logs = $query->paginate(30);

        $modules = AuditLog::selectRaw('DISTINCT module')->pluck('module');
        $actions = AuditLog::selectRaw('DISTINCT action')->pluck('action');

        return view('admin.audit-logs', compact('logs', 'modules', 'actions'));
    }

    public function managerIndex(Request $request)
    {
        $query = AuditLog::with('user')
            ->whereIn('module', ['Booking', 'Payment', 'CheckIn', 'Review'])
            ->orderBy('created_at', 'desc');

        if ($request->module) {
            $query->where('module', $request->module);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(30);
        $modules = ['Booking', 'Payment', 'CheckIn', 'Review'];

        return view('manager.audit-logs', compact('logs', 'modules'));
    }
}
