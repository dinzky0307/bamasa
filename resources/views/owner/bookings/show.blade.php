@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <a href="{{ route('owner.bookings.index') }}" class="text-blue-600 text-sm">&larr; Back to bookings</a>

    <h1 class="text-2xl font-bold mt-4">Booking #{{ $booking->id }}</h1>

    @if(session('success'))
        <div class="mt-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-4 bg-white shadow rounded p-4 space-y-3">
        <div>
            <h2 class="font-semibold text-gray-700">Guest</h2>
            <p>{{ $booking->user->name ?? 'Guest' }} (ID: {{ $booking->user_id }})</p>
        </div>

        <div>
            <h2 class="font-semibold text-gray-700">Dates</h2>
            <p>
                {{ $booking->check_in->format('M d, Y') }} &rarr; {{ $booking->check_out->format('M d, Y') }}
                ({{ $booking->guests }} guests)
            </p>
        </div>

        <div>
            <h2 class="font-semibold text-gray-700">Status</h2>
            <p class="uppercase text-sm">{{ $booking->status }}</p>
        </div>

        @if($booking->total_price)
            <div>
                <h2 class="font-semibold text-gray-700">Estimated Total Price</h2>
                <p>₱{{ number_format($booking->total_price, 2) }}</p>
            </div>
        @endif

        @if($booking->notes)
            <div>
                <h2 class="font-semibold text-gray-700">Guest Notes</h2>
                <p class="text-gray-700">{{ $booking->notes }}</p>
            </div>
        @endif

        @if($booking->owner_reply)
            <div>
                <h2 class="font-semibold text-gray-700">Your Reply</h2>
                <p class="text-gray-700">{{ $booking->owner_reply }}</p>
            </div>
        @endif
    </div>

    {{-- Actions --}}
    @if(in_array($booking->status, ['pending']))
        <div class="mt-6 bg-white shadow rounded p-4">
            <h2 class="font-semibold mb-2">Take Action</h2>

            <form method="POST" action="{{ route('owner.bookings.approve', $booking->id) }}" class="mb-3">
                @csrf
                <label class="block text-sm font-semibold mb-1">Optional message to guest (approval)</label>
                <textarea name="owner_reply" rows="3" class="w-full border rounded px-3 py-2 mb-2"
                          placeholder="Your reservation is confirmed..."></textarea>

                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Approve Booking
                </button>
            </form>

            <form method="POST" action="{{ route('owner.bookings.decline', $booking->id) }}">
                @csrf
                <label class="block text-sm font-semibold mb-1">Reason / message for decline</label>
                <textarea name="owner_reply" rows="3" class="w-full border rounded px-3 py-2 mb-2"
                          placeholder="We’re unable to accommodate your reservation because..."></textarea>

                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Decline Booking
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
