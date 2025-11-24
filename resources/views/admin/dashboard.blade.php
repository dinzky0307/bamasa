@extends('layouts.admin')


@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">

    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

    {{-- METRICS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Total Users --}}
        <div class="bg-white shadow rounded p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 uppercase">Total Users</p>
            <p class="text-3xl font-bold mt-1">{{ $totalUsers }}</p>
        </div>

        {{-- Total Businesses --}}
        <div class="bg-white shadow rounded p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 uppercase">Total Businesses</p>
            <p class="text-3xl font-bold mt-1">{{ $totalBusinesses }}</p>
        </div>

        {{-- Pending Business Approvals --}}
        <div class="bg-white shadow rounded p-6 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500 uppercase">Pending Approvals</p>
            <p class="text-3xl font-bold mt-1">{{ $pendingBusinesses }}</p>
        </div>

        {{-- Total Bookings --}}
        <div class="bg-white shadow rounded p-6 border-l-4 border-purple-500">
            <p class="text-sm text-gray-500 uppercase">Total Bookings</p>
            <p class="text-3xl font-bold mt-1">{{ $totalBookings }}</p>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- QUICK LINKS --}}
        <div class="bg-white shadow rounded p-5 h-fit">
            <h2 class="text-lg font-semibold mb-3">Quick Links</h2>
            <ul class="space-y-2">

                <li>
                    <a href="{{ route('admin.businesses') }}"
                        class="flex items-center justify-between p-3 bg-blue-50 hover:bg-blue-100 rounded transition">
                        <span>Manage Businesses</span>
                        <span class="text-blue-600">&rarr;</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.bookings') }}"
                        class="flex items-center justify-between p-3 bg-green-50 hover:bg-green-100 rounded transition">
                        <span>View All Bookings</span>
                        <span class="text-green-600">&rarr;</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users') }}"
                        class="flex items-center justify-between p-3 bg-purple-50 hover:bg-purple-100 rounded transition">
                        <span>View All Users</span>
                        <span class="text-purple-600">&rarr;</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.analytics') }}"
                        class="flex items-center justify-between p-3 bg-orange-50 hover:bg-orange-100 rounded transition">
                        <span>Analytics Dashboard</span>
                        <span class="text-orange-600">&rarr;</span>
                    </a>
                </li>

            </ul>
        </div>

        {{-- RECENT BOOKINGS --}}
        <div class="md:col-span-2 bg-white shadow rounded p-5">
            <h2 class="text-lg font-semibold mb-3">Recent Bookings</h2>

            @if($recentBookings->isEmpty())
                <p class="text-gray-500">No recent bookings found.</p>
            @else
                <ul class="divide-y">

                    @foreach($recentBookings as $booking)
                        <li class="py-4 flex items-center justify-between">

                            {{-- Booking Info --}}
                            <div>
                                <p class="font-semibold">
                                    {{ $booking->user->name ?? 'Guest' }}
                                    <span class="text-gray-400 text-sm">→</span>
                                    {{ $booking->business->name ?? 'Business deleted' }}
                                </p>

                                <p class="text-sm text-gray-600">
                                    {{ optional($booking->check_in)->format('M d, Y') }}
                                    –
                                    {{ optional($booking->check_out)->format('M d, Y') }}
                                </p>

                                <span class="text-xs px-2 py-1 rounded mt-1 inline-block
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                    @elseif($booking->status === 'declined') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>

                            {{-- Arrow Icon --}}
                            <a href="{{ route('admin.bookings') }}"
                                class="text-blue-600 text-lg hover:text-blue-800">&rarr;</a>
                        </li>
                    @endforeach

                </ul>
            @endif
        </div>
    </div>

</div>
@endsection
