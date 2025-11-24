<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingRequestNotification;

class BookingController extends Controller
{
    // Show booking form
    public function create(Business $business)
    {
        if ($business->status !== 'approved') {
            abort(404);
        }

        return view('bookings.create', [
            'business' => $business,
        ]);
    }

    // Handle booking form submit
    public function store(Request $request, Business $business)
    {
        if ($business->status !== 'approved') {
            abort(404);
        }

        $data = $request->validate([
            'check_in'  => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests'    => ['required', 'integer', 'min:1'],
            'notes'     => ['nullable', 'string', 'max:1000'],
        ]);

        // Check availability (no overlapping approved or pending bookings)
        $overlap = Booking::where('business_id', $business->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('check_in', '<', $data['check_out'])
                      ->where('check_out', '>', $data['check_in']);
                });
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors(['check_in' => 'Selected dates are not available for this business. Please choose different dates.']);
        }

        // Compute nights
        $checkIn  = new \Carbon\Carbon($data['check_in']);
        $checkOut = new \Carbon\Carbon($data['check_out']);
        $nights   = $checkIn->diffInDays($checkOut);

        if ($nights <= 0) {
            return back()
                ->withInput()
                ->withErrors(['check_out' => 'Check-out date must be at least 1 day after check-in.']);
        }

        // Determine nightly rate (use min_price as base fallback)
        $nightlyRate = $business->min_price ?? $business->max_price ?? 0;
        $totalPrice  = $nightlyRate * $nights;

        $booking = Booking::create([
            'business_id' => $business->id,
            'user_id'     => Auth::id(),
            'check_in'    => $data['check_in'],
            'check_out'   => $data['check_out'],
            'guests'      => $data['guests'],
            'status'      => 'pending',
            'notes'       => $data['notes'] ?? null,
            'total_price' => $totalPrice,
        ]);

        // Send email notification to business email (if available)
        if ($business->email) {
            try {
                Mail::to($business->email)->send(new BookingRequestNotification($booking));
            } catch (\Throwable $e) {
                // For prototype, ignore mail failures
            }
        }

        return redirect()
            ->route('bookings.mine')
            ->with('success', 'Your booking request has been submitted. The business will review and confirm your reservation.');
    }

    // Live availability + price for given dates (called via fetch/JS)
    public function availability(Request $request, Business $business)
    {
        $data = $request->validate([
            'check_in'  => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

        $overlap = Booking::where('business_id', $business->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('check_in', '<', $data['check_out'])
                      ->where('check_out', '>', $data['check_in']);
                });
            })
            ->exists();

        $checkIn  = new \Carbon\Carbon($data['check_in']);
        $checkOut = new \Carbon\Carbon($data['check_out']);
        $nights   = max($checkIn->diffInDays($checkOut), 0);

        $nightlyRate = $business->min_price ?? $business->max_price ?? 0;
        $totalPrice  = $nightlyRate * $nights;

        return response()->json([
            'available'    => !$overlap,
            'nights'       => $nights,
            'nightly_rate' => $nightlyRate,
            'total_price'  => $totalPrice,
        ]);
    }

    // Tourist: view my bookings
    public function myBookings(Request $request)
{
    $user = auth()->user();

    $status = $request->query('status'); // optional filter

    $bookings = $user->bookings() // assuming hasMany in User model
        ->with('business')
        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('bookings.mine', compact('bookings', 'status'));
}
}
