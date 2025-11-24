@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Places to Stay & Eat</h1>
            <p class="text-gray-600 text-sm">
                Explore approved businesses in Bantayan Island and find your next stay.
            </p>
        </div>

        <a href="{{ route('home') }}"
           class="text-sm text-sky-700 hover:underline">
            ← Back to homepage
        </a>
    </div>

    {{-- FILTERS --}}
    <form method="GET" action="{{ route('businesses.index') }}" class="bg-white shadow rounded p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- Search --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                <input type="text" name="q"
                       value="{{ $search }}"
                       placeholder="Search by name or description"
                       class="w-full border rounded px-3 py-2 text-sm">
            </div>

            {{-- Municipality --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Municipality</label>
                <select name="municipality" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Any</option>
                    @foreach($municipalities as $mun)
                        <option value="{{ $mun }}" @selected($municipality === $mun)>{{ $mun }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                <select name="category" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Any</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected($category === $cat)>
                            {{ ucfirst(str_replace('_', ' ', $cat)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort --}}
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Sort by</label>
                <select name="sort" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="latest" @selected($sort === 'latest')>Newest</option>
                    <option value="price_asc" @selected($sort === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected($sort === 'price_desc')>Price: High to Low</option>
                    <option value="name_asc" @selected($sort === 'name_asc')>Name: A–Z</option>
                </select>
            </div>
        </div>

        {{-- Price range --}}
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="flex gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Min Price (₱)</label>
                    <input type="number" name="min_price"
                           value="{{ $minPrice }}"
                           class="w-full border rounded px-3 py-2 text-sm" min="0">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Max Price (₱)</label>
                    <input type="number" name="max_price"
                           value="{{ $maxPrice }}"
                           class="w-full border rounded px-3 py-2 text-sm" min="0">
                </div>
            </div>

            <div class="flex gap-2 md:justify-end">
                <button type="submit"
                        class="px-4 py-2 bg-sky-600 text-white text-sm rounded hover:bg-sky-700">
                    Apply filters
                </button>
                <a href="{{ route('businesses.index') }}"
                   class="px-4 py-2 border text-sm rounded text-gray-700 hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </div>
    </form>

    {{-- RESULTS --}}
    @if($businesses->isEmpty())
        <div class="bg-white shadow rounded p-6 text-center text-gray-500">
            No businesses found matching your filters.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($businesses as $business)
                <div class="bg-white shadow rounded overflow-hidden flex flex-col">
                    @if($business->thumbnail)
                        <img src="{{ asset('storage/'.$business->thumbnail) }}"
                             alt="{{ $business->name }}"
                             class="h-40 w-full object-cover">
                    @else
                        <div class="h-40 w-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                            No image
                        </div>
                    @endif

                    <div class="p-4 flex-1 flex flex-col">
                        <h2 class="font-semibold text-lg mb-1">
                            <a href="{{ route('businesses.show', $business->id) }}"
                               class="hover:text-sky-700">
                                {{ $business->name }}
                            </a>
                        </h2>
                        <p class="text-xs text-gray-500 mb-2">
                            {{ $business->municipality ?? 'Bantayan Island' }}
                            · {{ ucfirst(str_replace('_', ' ', $business->category)) }}
                        </p>
                        <p class="text-sm text-gray-700 line-clamp-3 mb-3">
                            {{ Str::limit($business->description, 120) }}
                        </p>

                        <div class="mt-auto flex items-center justify-between">
                            <div class="text-sm font-semibold text-sky-700">
                                @if($business->min_price && $business->max_price)
                                    ₱{{ number_format($business->min_price) }} – ₱{{ number_format($business->max_price) }}
                                @elseif($business->min_price)
                                    From ₱{{ number_format($business->min_price) }}
                                @else
                                    Price on inquiry
                                @endif
                            </div>

                            <a href="{{ route('businesses.show', $business->id) }}"
                               class="text-xs px-3 py-1 bg-sky-600 text-white rounded hover:bg-sky-700">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $businesses->links() }}
        </div>
    @endif
</div>
@endsection
