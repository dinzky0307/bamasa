@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">

    <a href="{{ route('businesses.index') }}" class="text-blue-600">&larr; Back to Listings</a>

    <h1 class="text-4xl font-bold mt-4">{{ $business->name }}</h1>

    <p class="text-lg text-gray-600 mt-2">
        {{ ucfirst($business->category) }}
    </p>

    <div class="mt-6 space-y-4">

        @if($business->description)
            <div>
                <h2 class="text-2xl font-semibold">Description</h2>
                <p class="text-gray-700 mt-2">{{ $business->description }}</p>
            </div>
        @endif

        <div>
            <h2 class="text-2xl font-semibold">Details</h2>
            <ul class="mt-2 text-gray-700 space-y-1">
                <li><strong>Municipality:</strong> {{ $business->municipality }}</li>
                <li><strong>Address:</strong> {{ $business->address }}</li>

                @if($business->phone)
                    <li><strong>Phone:</strong> {{ $business->phone }}</li>
                @endif

                @if($business->email)
                    <li><strong>Email:</strong> {{ $business->email }}</li>
                @endif

                @if($business->website)
                    <li><strong>Website:</strong> 
                        <a href="{{ $business->website }}" target="_blank" class="text-blue-600">
                            Visit Website
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <div>
            <h2 class="text-2xl font-semibold">Price Range</h2>
            <p class="text-gray-700 mt-2">
                @if($business->min_price && $business->max_price)
                    ₱{{ number_format($business->min_price) }} - ₱{{ number_format($business->max_price) }}
                @else
                    Not specified
                @endif
            </p>
        </div>

    </div>

    {{-- Booking button for next module --}}
   <div class="mt-8">
    @auth
        <a href="{{ route('bookings.create', $business->id) }}"
           class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Make a Booking
        </a>
    @else
        <a href="{{ route('login') }}"
           class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Login to Book
        </a>
    @endauth
</div>

</div>
@endsection
