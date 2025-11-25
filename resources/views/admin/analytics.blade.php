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
