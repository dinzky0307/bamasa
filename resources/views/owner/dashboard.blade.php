@extends('layouts.owner')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-4">Business Owner Dashboard</h1>

    <p class="text-gray-700 mb-6">
        Welcome, {{ auth()->user()->name }}. Managing bookings for
        <strong>{{ $business->name }}</strong>
        (status: <span class="uppercase">{{ $business->status }}</span>).
    </p>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-sm text-gray-500">Total Bookings</h2>
            <p class="text-2xl font-bold mt-2">{{ $totalBookings }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-sm text-gray-500">Pending</h2>
            <p class="text-2xl font-bold mt-2 text-yellow-600">{{ $pendingBookings }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-sm text-gray-500">Approved</h2>
            <p class="text-2xl font-bold mt-2 text-green-600">{{ $approvedBookings }}</p>
        </div>
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-sm text-gray-500">Declined</h2>
            <p class="text-2xl font-bold mt-2 text-red-600">{{ $declinedBookings }}</p>
        </div>
    </div>

    {{-- Upcoming bookings --}}
    <div class="bg-white shadow rounded p-4 mb-6">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-xl font-semibold">Upcoming Bookings</h2>
            <a href="{{ route('owner.bookings.index') }}" class="text-blue-600 text-sm">
                View all bookings &rarr;
            </a>
        </div>

        @if($upcomingBookings->isEmpty())
            <p class="text-gray-500">No upcoming bookings yet.</p>
        @else
            <div class="divide-y">
                @foreach($upcomingBookings as $booking)
                    <div class="py-3 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">
                                {{ $booking->user->name ?? 'Guest' }}
                                &middot; {{ $booking->guests }} guests
                            </p>
                            <p class="text-gray-600 text-sm">
                                {{ $booking->check_in->format('M d, Y') }} &rarr;
                                {{ $booking->check_out->format('M d, Y') }}
                                ({{ ucfirst($booking->status) }})
                            </p>
                        </div>
                        <a href="{{ route('owner.bookings.show', $booking->id) }}"
                           class="text-blue-600 text-sm">
                            View &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
