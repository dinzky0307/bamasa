<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attraction;
use App\Models\Review;

class AdminController extends Controller
{
    /**
     * Admin home dashboard.
     */
    public function dashboard()
    {
        // Business stats
        $totalBusinesses      = Business::count();
        $approvedBusinesses   = Business::where('status', 'approved')->count();
        $pendingBusinesses    = Business::where('status', 'pending')->count();
        $rejectedBusinesses   = Business::where('status', 'rejected')->count();

        // Booking stats
        $totalBookings        = Booking::count();
        $pendingBookings      = Booking::where('status', 'pending')->count();
        $approvedBookings     = Booking::where('status', 'approved')->count();
        $declinedBookings     = Booking::where('status', 'declined')->count();

        // User stats
        $totalUsers           = User::count();
        $touristsCount        = User::where('role', 'tourist')->count();
        $businessOwnersCount  = User::where('role', 'business')->count();
        $adminCount           = User::where('role', 'admin')->count();

        // Recent activity
        $recentBookings = Booking::with(['user', 'business'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $pendingBusinessList = Business::with('user')
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBusinesses',
            'approvedBusinesses',
            'pendingBusinesses',
            'rejectedBusinesses',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'declinedBookings',
            'totalUsers',
            'touristsCount',
            'businessOwnersCount',
            'adminCount',
            'recentBookings',
            'pendingBusinessList'
        ));
    }

    /**
     * Manage businesses list.
     */
    public function businesses(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $query = Business::with('user');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('municipality', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Pending → approved → rejected
        $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
              ->orderBy('created_at', 'desc');

        $businesses = $query->paginate(15)->withQueryString();

        return view('admin.businesses.index', compact('businesses', 'status', 'search'));
    }

    /**
     * Approve a business listing.
     */
    public function approveBusiness(Request $request, Business $business)
    {
        $business->status = 'approved';
        $business->save();

        return back()->with('success', 'Business has been approved.');
    }

    /**
     * Reject a business listing.
     */
    public function rejectBusiness(Request $request, Business $business)
    {
        $business->status = 'rejected';
        $business->save();

        return back()->with('success', 'Business has been rejected.');
    }

    /**
     * Admin bookings overview.
     */
    public function bookings(Request $request)
    {
        $status = $request->query('status');
        $sort   = $request->query('sort', 'latest'); // latest | oldest
        $search = $request->query('q');

        $query = Booking::with(['user', 'business']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('business', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('municipality', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $bookings = $query->paginate(20)->withQueryString();

        // Status counts for filters
        $statusCounts = [
            'pending'   => Booking::where('status', 'pending')->count(),
            'approved'  => Booking::where('status', 'approved')->count(),
            'declined'  => Booking::where('status', 'declined')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact(
            'bookings',
            'status',
            'sort',
            'search',
            'statusCounts'
        ));
    }

    /**
     * Admin users overview.
     */
    public function users(Request $request)
    {
        $role   = $request->query('role');
        $search = $request->query('q');

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users', 'role', 'search'));
    }

        /**
     * Admin: manage attractions.
     */
    public function attractions(Request $request)
    {
        $status = $request->query('status'); // if you later add a status field
        $search = $request->query('q');

        $query = Attraction::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('municipality', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // If you added a 'status' column to attractions (e.g. published/draft), you can filter:
        if ($status) {
            $query->where('status', $status);
        }

        $attractions = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.attractions.index', compact('attractions', 'status', 'search'));
    }

    /**
     * AJAX: businesses table data.
     */
    public function ajaxBusinesses(Request $request)
    {
        $status  = $request->query('status');
        $search  = $request->query('q');
        $perPage = (int) $request->query('per_page', 10);

        $query = Business::with('user');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('municipality', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
              ->orderBy('created_at', 'desc');

        $paginator = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * AJAX: bookings table data.
     */
    public function ajaxBookings(Request $request)
    {
        $status  = $request->query('status');
        $search  = $request->query('q');
        $sort    = $request->query('sort', 'latest');
        $perPage = (int) $request->query('per_page', 10);

        $query = Booking::with(['user', 'business']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('business', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('municipality', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $paginator = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * AJAX: users table data.
     */
    public function ajaxUsers(Request $request)
    {
        $role    = $request->query('role');
        $search  = $request->query('q');
        $perPage = (int) $request->query('per_page', 10);

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->orderBy('name');

        $paginator = $query->paginate($perPage)->withQueryString();

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * Admin analytics dashboard.
     * Includes local vs international tourists and all chart data.
     */
    public function analytics()
    {
        $today        = Carbon::today();
        $startOfWeek  = $today->copy()->startOfWeek();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfYear  = $today->copy()->startOfYear();

        // Helper to get local vs foreign tourists within a time range
        $countByPeriod = function (Carbon $startDate, Carbon $endDate) {
            $bookings = Booking::with('user')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $local = $bookings->filter(function ($booking) {
                $country = optional($booking->user)->country ?? 'Philippines';
                return strcasecmp($country, 'Philippines') === 0;
            })->count();

            $foreign = $bookings->filter(function ($booking) {
                $country = optional($booking->user)->country ?? 'Philippines';
                return strcasecmp($country, 'Philippines') !== 0;
            })->count();

            return [
                'local'   => $local,
                'foreign' => $foreign,
            ];
        };

        // Local vs international tourists by period
        $dailyStats = $countByPeriod(
            $today->copy()->startOfDay(),
            $today->copy()->endOfDay()
        );

        $weeklyStats = $countByPeriod(
            $startOfWeek,
            $today->copy()->endOfDay()
        );

        $monthlyStats = $countByPeriod(
            $startOfMonth,
            $today->copy()->endOfDay()
        );

        $yearlyStats = $countByPeriod(
            $startOfYear,
            $today->copy()->endOfDay()
        );

        // Basic aggregate numbers
        $totalBookings   = Booking::count();
        $totalBusinesses = Business::count();
        $totalUsers      = User::count();

        // BOOKINGS PER DAY (last 7 days)
        $bookingsPerDay = Booking::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $today->copy()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($row) {
                return [
                    Carbon::parse($row->date)->format('M d') => $row->count,
                ];
            });

        // BOOKINGS PER MONTH (last 12 months)
        $startOf12MonthsAgo = $today->copy()->subMonths(11)->startOfMonth();

        $bookingsPerMonth = Booking::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as count")
            ->where('created_at', '>=', $startOf12MonthsAgo)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->mapWithKeys(function ($row) {
                $label = Carbon::createFromFormat('Y-m', $row->ym)->format('M Y');
                return [$label => $row->count];
            });

        // BUSINESSES PER MUNICIPALITY
        $businessesByMunicipality = Business::selectRaw('municipality, COUNT(*) as count')
            ->groupBy('municipality')
            ->orderBy('municipality')
            ->get()
            ->mapWithKeys(function ($row) {
                $label = $row->municipality ?: 'Unspecified';
                return [$label => $row->count];
            });

        // USERS PER ROLE
        $usersByRole = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->orderBy('role')
            ->get()
            ->mapWithKeys(function ($row) {
                $label = ucfirst($row->role ?? 'Unknown');
                return [$label => $row->count];
            });

        // BOOKINGS BY STATUS
        $bookingsByStatus = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->orderBy('status')
            ->get()
            ->mapWithKeys(function ($row) {
                $label = ucfirst($row->status ?? 'Unknown');
                return [$label => $row->count];
            });


            // MOST VISITED ATTRACTION (by visits)
$mostVisitedAttraction = Attraction::orderByDesc('visits')
    ->select('id', 'name', 'municipality', 'visits')
    ->first();

// MOST PREFERRED ACCOMMODATION (by booking count)
$topAccommodationByBookings = Booking::selectRaw('business_id, COUNT(*) as total_bookings')
    ->whereNotNull('business_id')
    ->groupBy('business_id')
    ->orderByDesc('total_bookings')
    ->with('business') // relies on Booking->business relationship
    ->first();

// STAY DURATION STATS (longest, shortest, average)

$stayStats = [
    'longest'  => null,
    'shortest' => null,
    'average'  => null,
];

$stayQuery = Booking::whereNotNull('check_in')
    ->whereNotNull('check_out')
    ->whereRaw('DATEDIFF(check_out, check_in) > 0');

if ($stayQuery->count() > 0) {
    $stayStats['longest'] = $stayQuery
        ->selectRaw('MAX(DATEDIFF(check_out, check_in)) as longest')
        ->value('longest');

    $stayStats['shortest'] = $stayQuery
        ->selectRaw('MIN(DATEDIFF(check_out, check_in)) as shortest')
        ->value('shortest');

    $stayStats['average'] = round(
        $stayQuery
            ->selectRaw('AVG(DATEDIFF(check_out, check_in)) as average')
            ->value('average'),
        1
    );
}


// SATISFACTION / RATINGS STATS
$satisfactionStats = [
    'overall_average' => null,
    'total_reviews'   => 0,
    'top_rated'       => collect(),
];

$satisfactionStats['overall_average'] = Review::whereNotNull('rating')->avg('rating');
$satisfactionStats['total_reviews']   = Review::whereNotNull('rating')->count();

// top 5 accommodations by average rating (min 3 reviews)
$topRatedBusinesses = Review::whereNotNull('rating')
    ->selectRaw('business_id, AVG(rating) as avg_rating, COUNT(*) as review_count')
    ->groupBy('business_id')
    ->having('review_count', '>=', 3)
    ->orderByDesc('avg_rating')
    ->orderByDesc('review_count')
    ->take(5)
    ->get();

$businessMap = Business::whereIn('id', $topRatedBusinesses->pluck('business_id'))
    ->get()
    ->keyBy('id');

$satisfactionStats['top_rated'] = $topRatedBusinesses->map(function ($row) use ($businessMap) {
    $biz = $businessMap->get($row->business_id);
    return [
        'business_name' => $biz?->name ?? 'Unknown',
        'municipality'  => $biz?->municipality ?? null,
        'avg_rating'    => round($row->avg_rating, 2),
        'review_count'  => $row->review_count,
    ];
});

        return view('admin.analytics', compact(
            'dailyStats',
            'weeklyStats',
            'monthlyStats',
            'yearlyStats',
            'totalBookings',
            'totalBusinesses',
            'totalUsers',
            'bookingsPerDay',
            'bookingsPerMonth',
            'businessesByMunicipality',
            'usersByRole',
            'bookingsByStatus',
            'mostVisitedAttraction',
            'topAccommodationByBookings',
            'stayStats',
            'satisfactionStats'
        ));
    }
}
