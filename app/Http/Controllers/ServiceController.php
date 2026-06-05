<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        return view('services.index', compact('services'));
    }

    public function addToBooking(Request $request, Booking $booking)
    {
        if ($booking->guest_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $price = $service->price * $validated['quantity'];

        $booking->services()->attach($validated['service_id'], [
            'quantity' => $validated['quantity'],
            'price' => $price,
        ]);

        $servicesTotal = $booking->services()->sum('booking_service.price');
        $booking->update([
            'services_total' => $servicesTotal,
            'total' => $booking->subtotal + $servicesTotal - $booking->discount,
        ]);

        return back()->with('success', 'Service added to booking.');
    }

    public function removeFromBooking(Booking $booking, Service $service)
    {
        if ($booking->guest_id !== Auth::id() && !Auth::user()->isAdmin() && !Auth::user()->isReceptionist()) {
            abort(403);
        }

        $booking->services()->detach($service->id);

        $servicesTotal = $booking->services()->sum('booking_service.price');
        $booking->update([
            'services_total' => $servicesTotal,
            'total' => $booking->subtotal + $servicesTotal - $booking->discount,
        ]);

        return back()->with('success', 'Service removed from booking.');
    }
}
