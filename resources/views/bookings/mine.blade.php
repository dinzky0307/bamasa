@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">My Bookings</h1>
            <p class="text-gray-600 text-sm">
                View and track your reservations in Bantayan Island.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('businesses.index') }}"
               class="px-4 py-2 text-sm bg-sky-600 text-white rounded hover:bg-sky-700">
                Find a place to stay
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap gap-2 text-sm">
        @php
            $statuses = [null => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'declined' => 'Declined'];
        @endphp

        @foreach($statuses as $value => $label)
            <a href="{{ route('bookings.mine', $value ? ['status' => $value] : []) }}"
               class="px-3 py-1 rounded border
                    {{ ($status ?? null) === $value ? 'bg-sky-600 text-white border-sky-600' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white shadow rounded p-6 text-center text-gray-500">
            You don’t have any bookings yet.
            <a href="{{ route('businesses.index') }}" class="text-sky-600 underline">
                Browse businesses
            </a>
        </div>
    @else
        <div class="bg-white shadow rounded divide-y">
            @foreach($bookings as $booking)
                <div class="p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                    {{-- Left: Business + dates --}}
                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ $booking->business->name ?? 'Business deleted' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            @if($booking->business)
                                {{ $booking->business->municipality ?? '' }}
                                ·
                            @endif
                            @if($booking->check_in && $booking->check_out)
                                {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}
                                →
                                {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}
                            @else
                                <span class="text-red-600 font-medium">Dates not set</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Booked on {{ $booking->created_at->format('M d, Y') }}
                            · {{ $booking->guests ?? 1 }} guest{{ ($booking->guests ?? 1) > 1 ? 's' : '' }}
                        </p>
                    </div>

                    {{-- Right: Status + actions --}}
                    <div class="flex flex-col items-start md:items-end gap-2">

                        {{-- Status badge --}}
                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status === 'approved') bg-green-100 text-green-800
                            @elseif($booking->status === 'declined') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($booking->status ?? 'pending') }}
                        </span>

                        {{-- (Optional) Show simple “instructions” based on status --}}
                        <p class="text-xs text-gray-500">
                            @if($booking->status === 'pending')
                                Awaiting confirmation from the business.
                            @elseif($booking->status === 'approved')
                                Your booking is confirmed. Please keep your email/phone available.
                            @elseif($booking->status === 'declined')
                                This booking was declined. You may book another place.
                            @endif
                        </p>

                        {{-- Future: cancel / contact buttons could go here --}}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $bookings->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
