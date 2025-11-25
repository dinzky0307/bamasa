<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BusinessController extends Controller
{

   public function show(Request $request, Business $business)
{
    // Only show approved businesses publicly
    if ($business->status !== 'approved') {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(404);
        }
    }

    // Load relations
    $business->load('images', 'bookings');

    // Read ?month=YYYY-MM from query
    $monthParam = $request->query('month');

    if ($monthParam && preg_match('/^\d{4}-\d{2}$/', $monthParam)) {
        [$year, $month] = explode('-', $monthParam);
        $currentMonth = Carbon::createFromDate((int)$year, (int)$month, 1);
    } else {
        $currentMonth = Carbon::today()->startOfMonth();
    }

    $start = $currentMonth->copy()->startOfMonth();
    $end   = $currentMonth->copy()->endOfMonth();

    // Get bookings that overlap this month
    $bookings = $business->bookings()
        ->whereDate('check_in', '<=', $end)
        ->whereDate('check_out', '>=', $start)
        ->get();

    $unavailableDates = [];

    foreach ($bookings as $booking) {
        if (!$booking->check_in || !$booking->check_out) {
            continue;
        }

        $periodStart = Carbon::parse($booking->check_in)->max($start);
        $periodEnd   = Carbon::parse($booking->check_out)->min($end);

        for ($date = $periodStart->copy(); $date->lte($periodEnd); $date->addDay()) {
            $unavailableDates[] = $date->toDateString(); // 'YYYY-MM-DD'
        }
    }

    $unavailableDates = array_values(array_unique($unavailableDates));

    // For navigation
    $prevMonth = $currentMonth->copy()->subMonth();
    $nextMonth = $currentMonth->copy()->addMonth();

    return view('businesses.show', compact(
        'business',
        'unavailableDates',
        'currentMonth',
        'prevMonth',
        'nextMonth'
    ));
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
