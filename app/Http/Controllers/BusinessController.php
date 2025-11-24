<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    // Display all approved businesses
    public function index(Request $request)
{
    $query = Business::where('status', 'approved');

    // Keyword search
    if ($search = $request->input('q')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Municipality filter
    if ($municipality = $request->input('municipality')) {
        $query->where('municipality', $municipality);
    }

    // We’re not actually filtering by dates yet (that’s more complex),
    // but we accept them so they can be used later if you want.

    $businesses = $query->orderBy('name')
                        ->paginate(12)
                        ->withQueryString();

    return view('businesses.index', compact('businesses'));
}

    // Business detail page
    public function show(Business $business)
    {
        // Redirect if not approved (public should not see pending)
        if ($business->status !== 'approved') {
            abort(404);
        }

        return view('businesses.show', compact('business'));
    }
}
