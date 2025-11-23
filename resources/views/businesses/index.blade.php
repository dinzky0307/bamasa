@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-6">Bantayan Island Businesses</h1>

    @if($businesses->count() === 0)
        <p>No businesses found.</p>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($businesses as $business)
            <a href="{{ route('businesses.show', $business->id) }}"
               class="block bg-white shadow rounded-lg p-5 hover:shadow-xl transition">

                <h2 class="text-xl font-semibold">{{ $business->name }}</h2>
                <p class="text-sm text-gray-600">{{ ucfirst($business->category) }}</p>

                <p class="mt-2 text-gray-700 line-clamp-3">
                    {{ Str::limit($business->description, 120) }}
                </p>

                <div class="mt-3 text-sm text-gray-500">
                    <strong>Municipality:</strong> {{ $business->municipality ?? 'N/A' }}
                </div>

                <div class="mt-1 text-sm text-gray-500">
                    <strong>Price Range:</strong>
                    @if($business->min_price && $business->max_price)
                        ₱{{ number_format($business->min_price) }} - ₱{{ number_format($business->max_price) }}
                    @else
                        Not specified
                    @endif
                </div>

            </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $businesses->links() }} {{-- Pagination --}}
    </div>
</div>
@endsection
