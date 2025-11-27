@extends('layouts.admin')


@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">

    <h1 class="text-3xl font-bold mb-6">Admin Analytics Dashboard</h1>

    {{-- Grid Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Bookings per Month --}}
        <div class="bg-white p-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-2">Bookings per Month</h2>
            <canvas id="bookingsChart"></canvas>
        </div>

        {{-- Businesses per Municipality --}}
        <div class="bg-white p-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-2">Businesses by Municipality</h2>
            <canvas id="municipalityChart"></canvas>
        </div>

        {{-- Users by Role --}}
        <div class="bg-white p-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-2">Users per Role</h2>
            <canvas id="rolesChart"></canvas>
        </div>

        {{-- Booking Status --}}
        <div class="bg-white p-4 shadow rounded">
            <h2 class="text-lg font-semibold mb-2">Booking Status Distribution</h2>
            <canvas id="statusChart"></canvas>
        </div>

        {{-- KEY DESTINATIONS & ACCOMMODATION PREFERENCES --}}
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Most visited attraction --}}
    <div class="bg-white shadow rounded-xl border border-slate-100 p-5">
        <h2 class="text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">
            Mostly visited tourist attraction
        </h2>

        @if($mostVisitedAttraction)
            <p class="text-lg font-bold text-slate-900">
                {{ $mostVisitedAttraction->name }}
            </p>
            <p class="text-xs text-slate-500 mt-1">
                {{ $mostVisitedAttraction->municipality ?? 'Bantayan Island' }}
            </p>
            <p class="text-sm text-slate-600 mt-3">
                Estimated visits recorded: 
                <span class="font-semibold">{{ number_format($mostVisitedAttraction->visits) }}</span>
            </p>
        @else
            <p class="text-sm text-slate-500">
                No attraction data available yet.
            </p>
        @endif
    </div>

    {{-- Most preferred accommodation --}}
    <div class="bg-white shadow rounded-xl border border-slate-100 p-5">
        <h2 class="text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">
            Most preferred accommodation
        </h2>

        @if($topAccommodationByBookings && $topAccommodationByBookings->business)
            <p class="text-lg font-bold text-slate-900">
                {{ $topAccommodationByBookings->business->name }}
            </p>
            <p class="text-xs text-slate-500 mt-1">
                {{ $topAccommodationByBookings->business->municipality ?? 'Bantayan Island' }}
            </p>
            <p class="text-sm text-slate-600 mt-3">
                Total bookings recorded: 
                <span class="font-semibold">{{ $topAccommodationByBookings->total_bookings }}</span>
            </p>
        @else
            <p class="text-sm text-slate-500">
                No booking data available yet.
            </p>
        @endif
    </div>
</div>

{{-- LENGTH OF STAY --}}
<div class="mt-8 bg-white shadow rounded-xl border border-slate-100 p-5">
    <h2 class="text-sm font-semibold text-slate-700 mb-4 uppercase tracking-wide">
        Length of stay (tourist bookings)
    </h2>

    @if($stayStats['longest'])
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-slate-500 uppercase">Longest stay</p>
                <p class="text-2xl font-bold text-slate-900">
                    {{ $stayStats['longest'] }} <span class="text-sm font-normal">nights</span>
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase">Shortest stay</p>
                <p class="text-2xl font-bold text-slate-900">
                    {{ $stayStats['shortest'] }} <span class="text-sm font-normal">night(s)</span>
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase">Average stay</p>
                <p class="text-2xl font-bold text-slate-900">
                    {{ $stayStats['average'] }} <span class="text-sm font-normal">nights</span>
                </p>
            </div>
        </div>
    @else
        <p class="text-sm text-slate-500">
            No bookings with valid check-in/check-out dates yet.
        </p>
    @endif
</div>

{{-- LEVEL OF SATISFACTION (REVIEWS / RATINGS) --}}
<div class="mt-8 bg-white shadow rounded-xl border border-slate-100 p-5">
    <h2 class="text-sm font-semibold text-slate-700 mb-4 uppercase tracking-wide">
        Level of satisfaction (accommodation ratings)
    </h2>

    @if($satisfactionStats['total_reviews'] > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-4">
            <div>
                <p class="text-xs text-slate-500 uppercase">Overall average rating</p>
                <p class="text-2xl font-bold text-amber-500">
                    {{ number_format($satisfactionStats['overall_average'], 2) }}
                    <span class="text-sm text-slate-500">/ 5</span>
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase">Total reviews</p>
                <p class="text-2xl font-bold text-slate-900">
                    {{ $satisfactionStats['total_reviews'] }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-500 uppercase">Interpretation</p>
                @php
                    $avg = $satisfactionStats['overall_average'];
                    $label = 'No data';
                    if ($avg >= 4.5) $label = 'Excellent';
                    elseif ($avg >= 4) $label = 'Very Satisfied';
                    elseif ($avg >= 3) $label = 'Satisfied';
                    elseif ($avg >= 2) $label = 'Needs Improvement';
                    else $label = 'Poor';
                @endphp
                <p class="text-base font-semibold text-slate-800">
                    {{ $label }}
                </p>
            </div>
        </div>

        @if($satisfactionStats['top_rated']->isNotEmpty())
            <h3 class="text-xs font-semibold text-slate-600 mb-2 uppercase">
                Top-rated accommodations (min 3 reviews)
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr class="text-left text-[11px] font-semibold text-slate-500 uppercase">
                            <th class="px-2 py-2">Accommodation</th>
                            <th class="px-2 py-2">Municipality</th>
                            <th class="px-2 py-2">Avg. Rating</th>
                            <th class="px-2 py-2">Reviews</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($satisfactionStats['top_rated'] as $row)
                            <tr>
                                <td class="px-2 py-2">{{ $row['business_name'] }}</td>
                                <td class="px-2 py-2">{{ $row['municipality'] ?? '-' }}</td>
                                <td class="px-2 py-2 font-semibold text-amber-500">
                                    {{ $row['avg_rating'] }} / 5
                                </td>
                                <td class="px-2 py-2">{{ $row['review_count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <p class="text-sm text-slate-500">
            No ratings have been submitted yet.
        </p>
    @endif
</div>


        {{-- LOCAL VS INTERNATIONAL TOURISTS --}}
<h2 class="text-xl font-bold mb-4">Tourist Statistics</h2>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- DAILY --}}
    <div class="p-6 bg-white shadow rounded-lg border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-600 uppercase">
            Daily Visitors
        </h3>
        <p class="text-3xl font-bold mt-2 text-sky-700">
            {{ $dailyStats['local'] + $dailyStats['foreign'] }}
        </p>

        <div class="mt-3 text-sm space-y-1">
            <p class="text-green-600">
                • Local: <strong>{{ $dailyStats['local'] }}</strong>
            </p>
            <p class="text-indigo-600">
                • International: <strong>{{ $dailyStats['foreign'] }}</strong>
            </p>
        </div>
    </div>

    {{-- WEEKLY --}}
    <div class="p-6 bg-white shadow rounded-lg border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-600 uppercase">
            Weekly Visitors
        </h3>
        <p class="text-3xl font-bold mt-2 text-sky-700">
            {{ $weeklyStats['local'] + $weeklyStats['foreign'] }}
        </p>

        <div class="mt-3 text-sm space-y-1">
            <p class="text-green-600">
                • Local: <strong>{{ $weeklyStats['local'] }}</strong>
            </p>
            <p class="text-indigo-600">
                • International: <strong>{{ $weeklyStats['foreign'] }}</strong>
            </p>
        </div>
    </div>

    {{-- MONTHLY --}}
    <div class="p-6 bg-white shadow rounded-lg border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-600 uppercase">
            Monthly Visitors
        </h3>
        <p class="text-3xl font-bold mt-2 text-sky-700">
            {{ $monthlyStats['local'] + $monthlyStats['foreign'] }}
        </p>

        <div class="mt-3 text-sm space-y-1">
            <p class="text-green-600">
                • Local: <strong>{{ $monthlyStats['local'] }}</strong>
            </p>
            <p class="text-indigo-600">
                • International: <strong>{{ $monthlyStats['foreign'] }}</strong>
            </p>
        </div>
    </div>

    {{-- YEARLY --}}
    <div class="p-6 bg-white shadow rounded-lg border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-600 uppercase">
            Yearly Visitors
        </h3>
        <p class="text-3xl font-bold mt-2 text-sky-700">
            {{ $yearlyStats['local'] + $yearlyStats['foreign'] }}
        </p>

        <div class="mt-3 text-sm space-y-1">
            <p class="text-green-600">
                • Local: <strong>{{ $yearlyStats['local'] }}</strong>
            </p>
            <p class="text-indigo-600">
                • International: <strong>{{ $yearlyStats['foreign'] }}</strong>
            </p>
        </div>
    </div>

</div>



    </div>
</div>

{{-- CHART.JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // 1. BOOKINGS PER MONTH
    new Chart(document.getElementById('bookingsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($bookingsPerMonth->keys()) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($bookingsPerMonth->values()) !!},
                borderColor: 'blue',
                fill: false
            }]
        }
    });

    // 2. BUSINESSES PER MUNICIPALITY
    new Chart(document.getElementById('municipalityChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($businessesByMunicipality->keys()) !!},
            datasets: [{
                label: 'Businesses',
                data: {!! json_encode($businessesByMunicipality->values()) !!},
                backgroundColor: 'teal'
            }]
        }
    });

    // 3. USERS PER ROLE
    new Chart(document.getElementById('rolesChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($usersByRole->keys()) !!},
            datasets: [{
                data: {!! json_encode($usersByRole->values()) !!},
                backgroundColor: ['#60a5fa', '#34d399', '#f87171']
            }]
        }
    });

    // 4. BOOKINGS BY STATUS
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($bookingsByStatus->keys()) !!},
            datasets: [{
                data: {!! json_encode($bookingsByStatus->values()) !!},
                backgroundColor: ['#facc15', '#4ade80', '#f87171']
            }]
        }
    });

});
</script>

@endsection
