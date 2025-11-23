@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-4">My Bookings</h1>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($bookings->isEmpty())
        <p>You have not made any bookings yet.</p>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white shadow rounded p-4">
                    <h2 class="text-xl font-semibold">
                        {{ $booking->business->name ?? 'Business deleted' }}
                    </h2>
                    <p class="text-gray-600">
                        {{ $booking->check_in->format('M d, Y') }} &rarr; {{ $booking->check_out->format('M d, Y') }}<br>
                        Guests: {{ $booking->guests }}
                    </p>
                    <p class="mt-2">
                        <strong>Status:</strong>
                        <span class="uppercase text-sm">{{ $booking->status }}</span>
                    </p>
                    @if($booking->total_price)
                        <p><strong>Estimated Total:</strong> ₱{{ number_format($booking->total_price, 2) }}</p>
                    @endif
                    @if($booking->notes)
                        <p class="mt-2 text-gray-700"><strong>Your Notes:</strong> {{ $booking->notes }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
