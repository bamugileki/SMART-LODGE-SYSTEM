<?php

namespace App\Http\Controllers;

use App\Models\QuickLink;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isManager()) {
            return redirect()->route('manager.dashboard');
        }
        if ($user->isReceptionist()) {
            return redirect()->route('receptionist.dashboard');
        }

        $bookings = $user->bookings()->with('room')->orderBy('created_at', 'desc')->take(5)->get();
        $upcoming = $user->bookings()->whereIn('status', ['pending', 'confirmed'])->count();

        $quickLinks = QuickLink::getLinks('guest_dashboard');

        return view('dashboard', compact('bookings', 'upcoming', 'quickLinks'));
    }
}
