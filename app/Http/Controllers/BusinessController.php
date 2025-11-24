<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{

    public function show(Business $business)
{
    // Only show approved businesses to the public.
    // If you want admins/owners to see their own even if pending, you can relax this.
    if ($business->status !== 'approved') {
        // Optionally allow admin or the owner to see it:
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(404);
        }
    }

    return view('businesses.show', compact('business'));
}


    // Display all approved businesses
    public function index(Request $request)
{
    $query = Business::query()
        ->where('status', 'approved'); // only show approved businesses

    // Read filters from query string
    $search       = $request->query('q');
    $municipality = $request->query('municipality');
    $category     = $request->query('category');
    $minPrice     = $request->query('min_price');
    $maxPrice     = $request->query('max_price');
    $sort         = $request->query('sort');

    // Text search (name + description)
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Municipality filter
    if ($municipality) {
        $query->where('municipality', $municipality);
    }

    // Category filter
    if ($category) {
        $query->where('category', $category);
    }

    // Price range
    if ($minPrice !== null && $minPrice !== '') {
        $query->where('min_price', '>=', $minPrice);
    }

    if ($maxPrice !== null && $maxPrice !== '') {
        $query->where('max_price', '<=', $maxPrice);
    }

    // Sorting
    switch ($sort) {
        case 'price_asc':
            $query->orderBy('min_price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('min_price', 'desc');
            break;
        case 'name_asc':
            $query->orderBy('name', 'asc');
            break;
        case 'latest':
        default:
            $query->orderBy('created_at', 'desc');
            break;
    }

    $businesses = $query->paginate(9)->withQueryString();

    // For dropdowns
    $municipalities = ['Bantayan', 'Santa Fe', 'Madridejos'];
    $categories     = ['hotel', 'resort', 'homestay', 'restaurant', 'tour_operator'];

    return view('businesses.index', compact(
        'businesses',
        'municipalities',
        'categories',
        'search',
        'municipality',
        'category',
        'minPrice',
        'maxPrice',
        'sort'
    ));

    
}
}
