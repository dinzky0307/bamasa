<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerDashboardController extends Controller
{
    protected function getOwnerBusiness(): ?Business
    {
        return Business::where('user_id', Auth::id())->first();
    }

    // Main dashboard with stats
    public function dashboard()
    {
        $business = $this->getOwnerBusiness();

        if (!$business) {
            return view('owner.dashboard-no-business');
        }

        $baseQuery = Booking::where('business_id', $business->id);

        $totalBookings    = (clone $baseQuery)->count();
        $pendingBookings  = (clone $baseQuery)->where('status', 'pending')->count();
        $approvedBookings = (clone $baseQuery)->where('status', 'approved')->count();
        $declinedBookings = (clone $baseQuery)->where('status', 'declined')->count();

        $upcomingBookings = (clone $baseQuery)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('check_in', '>=', now()->toDateString())
            ->orderBy('check_in')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'business',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'declinedBookings',
            'upcomingBookings'
        ));
    }

    // Bookings list with filters
    public function bookings(Request $request)
    {
        $business = $this->getOwnerBusiness();

        if (!$business) {
            abort(403, 'You do not have a business profile yet.');
        }

        $status = $request->query('status'); // pending, approved, declined, cancelled, all

        $query = Booking::with('user')
            ->where('business_id', $business->id)
            ->orderBy('created_at', 'desc');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('owner.bookings.index', [
            'business' => $business,
            'bookings' => $bookings,
            'currentStatus' => $status ?? 'all',
        ]);
    }

    // Booking detail
    public function showBooking(Booking $booking)
    {
        $business = $this->getOwnerBusiness();

        if (!$business || $booking->business_id !== $business->id) {
            abort(403);
        }

        $booking->load('user', 'business');

        return view('owner.bookings.show', [
            'business' => $business,
            'booking'  => $booking,
        ]);
    }

    // Approve booking
    public function approveBooking(Request $request, Booking $booking)
    {
        $business = $this->getOwnerBusiness();

        if (!$business || $booking->business_id !== $business->id) {
            abort(403);
        }

        $booking->status = 'approved';
        $booking->owner_reply = $request->input('owner_reply');
        $booking->save();

        return redirect()
            ->route('owner.bookings.show', $booking->id)
            ->with('success', 'Booking approved successfully.');
    }

    // Decline booking
    public function declineBooking(Request $request, Booking $booking)
    {
        $business = $this->getOwnerBusiness();

        if (!$business || $booking->business_id !== $business->id) {
            abort(403);
        }

        $request->validate([
            'owner_reply' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking->status = 'declined';
        $booking->owner_reply = $request->input('owner_reply');
        $booking->save();

        return redirect()
            ->route('owner.bookings.show', $booking->id)
            ->with('success', 'Booking declined.');
    }

    public function editBusiness()
{
    $user = auth()->user();

    $business = Business::where('user_id', $user->id)->first();

    if (!$business) {
        return redirect()
            ->route('owner.dashboard')
            ->with('error', 'No business profile found for your account.');
    }

    return view('owner.business.edit', compact('business'));
}

public function updateBusiness(Request $request)
{
    $user = auth()->user();

    $business = Business::where('user_id', $user->id)->firstOrFail();

    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'category'      => 'required|string|max:255',
        'description'   => 'nullable|string',
        'address'       => 'nullable|string|max:255',
        'municipality'  => 'nullable|string|max:255',
        'phone'         => 'nullable|string|max:50',
        'email'         => 'nullable|email|max:255',
        'website'       => 'nullable|url|max:255',
        'facebook_page' => 'nullable|url|max:255',
        'min_price'     => 'nullable|numeric|min:0',
        'max_price'     => 'nullable|numeric|min:0',
        'thumbnail'     => 'nullable|image|max:2048', // 2MB
    ]);

    // Handle thumbnail upload
    if ($request->hasFile('thumbnail')) {
        if ($business->thumbnail && Storage::disk('public')->exists($business->thumbnail)) {
            Storage::disk('public')->delete($business->thumbnail);
        }

        $path = $request->file('thumbnail')->store('business-thumbnails', 'public');
        $validated['thumbnail'] = $path;
    }

    $business->update($validated);

    return redirect()
        ->route('owner.business.edit')
        ->with('success', 'Business profile updated successfully.');
}

public function analytics()
{
    $business = Business::where('user_id', auth()->id())->firstOrFail();

    $bookingsCount = $business->bookings()->count();
    $monthlyBookings = $business->bookings()
        ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->groupBy('month')
        ->pluck('total','month');

    return view('owner.analytics', compact('business', 'bookingsCount', 'monthlyBookings'));
}



}
