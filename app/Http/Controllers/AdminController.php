<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attraction;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    public function dashboard()
{
    return view('admin.dashboard', [
        'totalUsers'        => User::count(),
        'totalBusinesses'   => Business::count(),
        'pendingBusinesses' => Business::where('status', 'pending')->count(),
        'totalBookings'     => Booking::count(),
        'recentBookings'    => Booking::latest()->take(5)->get(),
    ]);
}


    public function businesses()
    {
        $businesses = Business::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')") // pending first
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.businesses.index', compact('businesses'));
    }

    public function approveBusiness(Business $business)
    {
        $business->status = 'approved';
        $business->save();

        return redirect()
            ->back()
            ->with('success', "Business '{$business->name}' has been approved.");
    }

    public function rejectBusiness(Business $business, Request $request)
    {
        $business->status = 'rejected';
        $business->save();

        return redirect()
            ->back()
            ->with('success', "Business '{$business->name}' has been rejected.");
    }

    public function bookings(Request $request)
{
    $status = $request->query('status', 'all');
    $sort = $request->query('sort', 'newest');
    $search = $request->query('q');

    $query = Booking::with(['business', 'user']);

    // Filter by status
    if ($status !== 'all') {
        $query->where('status', $status);
    }

    // Keyword search
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%");
            })->orWhereHas('business', function ($bq) use ($search) {
                $bq->where('name', 'like', "%{$search}%");
            });
        });
    }

    // Sorting
    switch ($sort) {
        case 'oldest':
            $query->orderBy('created_at', 'asc');
            break;
        case 'checkin_asc':
            $query->orderBy('check_in', 'asc');
            break;
        case 'checkin_desc':
            $query->orderBy('check_in', 'desc');
            break;
        case 'name_asc':
            $query->join('users', 'bookings.user_id', '=', 'users.id')
                  ->orderBy('users.name', 'asc');
            break;
        case 'name_desc':
            $query->join('users', 'bookings.user_id', '=', 'users.id')
                  ->orderBy('users.name', 'desc');
            break;
        default: // newest
            $query->orderBy('bookings.created_at', 'desc');
    }

    $bookings = $query->paginate(20)->withQueryString();

    return view('admin.bookings.index', compact('bookings', 'status', 'sort', 'search'));
}


    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function analytics()
{
    // 1. Bookings per month (last 12 months)
    $bookingsPerMonth = Booking::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
        ->where('created_at', '>=', now()->subYear())
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month');

    // 2. Businesses per municipality
    $businessesByMunicipality = Business::selectRaw('COUNT(*) as count, municipality')
        ->groupBy('municipality')
        ->pluck('count', 'municipality');

    // 3. User distribution by role
    $usersByRole = User::selectRaw('COUNT(*) as count, role')
        ->groupBy('role')
        ->pluck('count', 'role');

    // 4. Bookings by status
    $bookingsByStatus = Booking::selectRaw('COUNT(*) as count, status')
        ->groupBy('status')
        ->pluck('count', 'status');

    return view('admin.analytics', compact(
        'bookingsPerMonth',
        'businessesByMunicipality',
        'usersByRole',
        'bookingsByStatus'
    ));
}

public function ajaxBusinesses(Request $request)
{
    $search = $request->search;
    $sortBy = $request->sortBy ?? 'created_at';
    $direction = $request->direction ?? 'desc';

    $businesses = Business::with('user')
        ->when($search, fn($q) =>
            $q->where('name', 'like', "%$search%")
              ->orWhere('municipality', 'like', "%$search%")
              ->orWhere('status', 'like', "%$search%")
        )
        ->orderBy($sortBy, $direction)
        ->paginate(10);

    return view('admin.businesses.partials.table', compact('businesses'))->render();
}

// List attractions
public function attractions()
{
    $attractions = Attraction::orderBy('created_at', 'desc')->paginate(15);

    return view('admin.attractions.index', compact('attractions'));
}

// Show create form
public function createAttraction()
{
    $municipalities = ['Bantayan', 'Santa Fe', 'Madridejos'];
    $categories     = ['beach', 'church', 'landmark', 'park', 'food', 'activity'];

    return view('admin.attractions.form', [
        'attraction'     => new Attraction(),
        'municipalities' => $municipalities,
        'categories'     => $categories,
        'mode'           => 'create',
    ]);
}

// Store new attraction
public function storeAttraction(Request $request)
{
    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'category'      => 'nullable|string|max:50',
        'description'   => 'nullable|string',
        'municipality'  => 'nullable|string|max:100',
        'address'       => 'nullable|string|max:255',
        'latitude'      => 'nullable|numeric',
        'longitude'     => 'nullable|numeric',
        'opening_hours' => 'nullable|string|max:255',
        'entrance_fee'  => 'nullable|string|max:100',
        'status'        => 'required|in:published,draft',
        'thumbnail'     => 'nullable|image|max:2048',
    ]);

    $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();

    if ($request->hasFile('thumbnail')) {
        $validated['thumbnail'] = $request->file('thumbnail')->store('attractions', 'public');
    }

    Attraction::create($validated);

    return redirect()->route('admin.attractions')
        ->with('success', 'Attraction created successfully.');
}

// Show edit form
public function editAttraction(Attraction $attraction)
{
    $municipalities = ['Bantayan', 'Santa Fe', 'Madridejos'];
    $categories     = ['beach', 'church', 'landmark', 'park', 'food', 'activity'];

    return view('admin.attractions.form', [
        'attraction'     => $attraction,
        'municipalities' => $municipalities,
        'categories'     => $categories,
        'mode'           => 'edit',
    ]);
}

// Update
public function updateAttraction(Request $request, Attraction $attraction)
{
    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'category'      => 'nullable|string|max:50',
        'description'   => 'nullable|string',
        'municipality'  => 'nullable|string|max:100',
        'address'       => 'nullable|string|max:255',
        'latitude'      => 'nullable|numeric',
        'longitude'     => 'nullable|numeric',
        'opening_hours' => 'nullable|string|max:255',
        'entrance_fee'  => 'nullable|string|max:100',
        'status'        => 'required|in:published,draft',
        'thumbnail'     => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('thumbnail')) {
        $validated['thumbnail'] = $request->file('thumbnail')->store('attractions', 'public');
    }

    // Regenerate slug if name changed
    if ($attraction->name !== $validated['name']) {
        $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
    }

    $attraction->update($validated);

    return redirect()->route('admin.attractions')
        ->with('success', 'Attraction updated successfully.');
}

// Delete
public function deleteAttraction(Attraction $attraction)
{
    $attraction->delete();

    return redirect()->route('admin.attractions')
        ->with('success', 'Attraction deleted.');
}

}
