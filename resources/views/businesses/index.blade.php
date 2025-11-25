@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                Places to stay & eat in Bantayan Island
            </h1>
            <p class="text-sm text-slate-600 mt-1">
                Browse verified accommodations and restaurants across Bantayan, Santa Fe, and Madridejos.
            </p>
        </div>

        <div class="flex flex-col items-end text-right gap-1 text-xs">
            <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                {{ $businesses->total() }} places found
            </span>
            <a href="{{ route('home') }}" class="text-sky-700 hover:text-sky-900">
                ← Back to homepage
            </a>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <form method="GET" action="{{ route('businesses.index') }}" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 md:p-5 space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Search
                    </label>
                    <input type="text"
                           name="q"
                           value="{{ $search }}"
                           placeholder="Resort name, homestay, restaurant..."
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>

                {{-- Municipality --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Municipality
                    </label>
                    <select name="municipality"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Anywhere</option>
                        @foreach($municipalities as $mun)
                            <option value="{{ $mun }}" @selected($municipality === $mun)>{{ $mun }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Category
                    </label>
                    <select name="category"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Any type</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected($category === $cat)>
                                {{ ucfirst(str_replace('_', ' ', $cat)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Price + Sort --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm items-end">
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            Min price (₱)
                        </label>
                        <input type="number" name="min_price" min="0"
                               value="{{ $minPrice }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            Max price (₱)
                        </label>
                        <input type="number" name="max_price" min="0"
                               value="{{ $maxPrice }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Sort by
                    </label>
                    <select name="sort"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="latest" @selected($sort === 'latest')>Newest</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Price: High to Low</option>
                        <option value="name_asc" @selected($sort === 'name_asc')>Name: A–Z</option>
                    </select>
                </div>

                <div class="flex gap-2 md:justify-end">
                    <button type="submit"
                            class="px-4 py-2 bg-sky-600 text-white rounded-lg text-sm font-medium hover:bg-sky-700">
                        Apply filters
                    </button>
                    <a href="{{ route('businesses.index') }}"
                       class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-50">
                        Clear
                    </a>
                </div>
            </div>

            {{-- Active filters summary --}}
            @if($search || $municipality || $category || $minPrice || $maxPrice)
                <div class="pt-2 border-t text-xs text-slate-500 flex flex-wrap gap-2">
                    <span class="font-semibold text-slate-600">Active filters:</span>
                    @if($search)
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                            “{{ $search }}”
                        </span>
                    @endif
                    @if($municipality)
                        <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700">
                            {{ $municipality }}
                        </span>
                    @endif
                    @if($category)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </span>
                    @endif
                    @if($minPrice || $maxPrice)
                        <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">
                            @if($minPrice && $maxPrice)
                                ₱{{ number_format($minPrice) }} – ₱{{ number_format($maxPrice) }}
                            @elseif($minPrice)
                                From ₱{{ number_format($minPrice) }}
                            @else
                                Up to ₱{{ number_format($maxPrice) }}
                            @endif
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </form>

    {{-- RESULTS --}}
    @if($businesses->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-10 text-center text-slate-500 text-sm">
            No businesses found matching your filters. Try removing some filters or searching a different term.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($businesses as $business)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col hover:-translate-y-1 hover:shadow-md transition">
                    {{-- Image --}}
                    @if($business->thumbnail)
                        <img src="{{ asset('storage/'.$business->thumbnail) }}"
                             alt="{{ $business->name }}"
                             class="h-40 w-full object-cover">
                    @else
                        <div class="h-40 w-full bg-slate-200 flex items-center justify-center text-xs text-slate-500">
                            No image available
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h2 class="font-semibold text-base leading-snug">
                                <a href="{{ route('businesses.show', $business->id) }}"
                                   class="hover:text-sky-700">
                                    {{ $business->name }}
                                </a>
                            </h2>
                            <span class="text-[10px] px-2 py-0.5 rounded-full
                                @if($business->status === 'approved')
                                    bg-emerald-50 text-emerald-700 border border-emerald-100
                                @elseif($business->status === 'pending')
                                    bg-amber-50 text-amber-700 border border-amber-100
                                @else
                                    bg-slate-100 text-slate-600 border border-slate-200
                                @endif">
                                {{ ucfirst($business->status) }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 mb-2">
                            {{ $business->municipality ?? 'Bantayan Island' }}
                            · {{ ucfirst(str_replace('_', ' ', $business->category)) }}
                        </p>

                        <p class="text-sm text-slate-700 mb-3">
                            {{ \Illuminate\Support\Str::limit($business->description, 110) }}
                        </p>

                        <div class="mt-auto flex items-center justify-between pt-2 border-t border-slate-100">
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
                               class="text-xs px-3 py-1.5 rounded-full bg-sky-600 text-white hover:bg-sky-700">
                                View details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex items-center justify-between text-xs text-slate-500">
            <div>
                Showing
                <span class="font-semibold">{{ $businesses->firstItem() }}</span>
                –
                <span class="font-semibold">{{ $businesses->lastItem() }}</span>
                of
                <span class="font-semibold">{{ $businesses->total() }}</span>
                places
            </div>
            <div>
                {{ $businesses->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
