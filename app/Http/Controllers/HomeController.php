<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Show a few featured/approved businesses on the homepage
        $featuredBusinesses = Business::where('status', 'approved')
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Distinct list of municipalities for the search dropdown
        $municipalities = Business::where('status', 'approved')
            ->whereNotNull('municipality')
            ->pluck('municipality')
            ->unique()
            ->values();

        return view('home', compact('featuredBusinesses', 'municipalities'));
    }
}
