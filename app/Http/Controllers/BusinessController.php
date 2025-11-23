<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    // Display all approved businesses
    public function index()
    {
        $businesses = Business::where('status', 'approved')
                              ->orderBy('name')
                              ->paginate(12);

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
