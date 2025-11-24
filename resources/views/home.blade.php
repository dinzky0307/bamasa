@extends('layouts.app')

@section('content')
{{-- HERO WITH BACKGROUND IMAGE + SEARCH --}}
<div class="relative min-h-[70vh] flex items-center">
    {{-- Background image --}}
    <div class="absolute inset-0">
        <img
            src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=80"
            alt="Bantayan Island beach"
            class="w-full h-full object-cover"
        >
        {{-- Dark overlay --}}
        <div class="absolute inset-0 bg-black/50"></div>
    </div>

    {{-- Hero content --}}
    <div class="relative z-10 max-w-6xl mx-auto px-4 py-16 flex flex-col gap-8">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-10">
            <div class="flex-1 text-white">
                <p class="text-sm uppercase tracking-wide text-sky-200 font-semibold mb-2">
                    Bantayan Island Tourism Reservation Portal
                </p>

                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">
                    Discover where to stay
                    <br>
                    in <span class="text-sky-200">Bantayan Island</span>
                </h1>

                <p class="mt-4 text-sm md:text-lg text-sky-100 max-w-xl">
                    Browse accredited resorts, hotels, and homestays across Bantayan, Santa Fe, and Madridejos.
                    Send booking requests online and let local businesses confirm your stay.
                </p>

                <p class="mt-3 text-xs text-sky-100">
                    Create a free account as a tourist to track your reservations and booking status.
                </p>
            </div>

            {{-- Right-side info card (kept simple) --}}
            <div class="flex-1 w-full max-w-md">
                <div class="bg-white/95 backdrop-blur shadow-xl rounded-2xl px-6 py-5">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">
                        How it works
                    </h2>

                    <div class="mt-3 space-y-3 text-sm">
                        <div>
                            <p class="font-semibold text-gray-800">1 • Explore stays</p>
                            <p class="text-gray-600">Find accredited accommodations around Bantayan Island.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">2 • Send a request</p>
                            <p class="text-gray-600">Choose your dates and number of guests.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">3 • Get confirmed</p>
                            <p class="text-gray-600">Owners review and confirm through the system.</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t text-xs text-gray-500">
                        Managed in partnership with local tourism stakeholders of Bantayan Island.
                    </div>
                </div>
            </div>
        </div>

        {{-- SEARCH BAR --}}
        <form
            action="{{ route('businesses.index') }}"
            method="GET"
            class="bg-white/95 backdrop-blur shadow-xl rounded-2xl px-4 md:px-6 py-4 flex flex-col md:flex-row gap-3 md:items-end"
        >
            {{-- Keyword --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Where do you want to stay?
                </label>
                <input
                    type="text"
                    name="q"
                    placeholder="Resort name or keyword"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                >
            </div>

            {{-- Municipality --}}
            <div class="w-full md:w-48">
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Municipality
                </label>
                <select name="municipality" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Any</option>
                    @foreach($municipalities as $mun)
                        <option value="{{ $mun }}">{{ $mun }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Check-in --}}
            <div class="w-full md:w-40">
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Check-in
                </label>
                <input
                    type="date"
                    name="check_in"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                >
            </div>

            {{-- Check-out --}}
            <div class="w-full md:w-40">
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    Check-out
                </label>
                <input
                    type="date"
                    name="check_out"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                >
            </div>

            {{-- Submit --}}
            <div class="w-full md:w-auto">
                <button
                    type="submit"
                    class="w-full md:w-auto inline-flex items-center justify-center px-5 py-2.5 mt-1 md:mt-0 rounded-lg bg-sky-500 text-white font-semibold text-sm hover:bg-sky-600"
                >
                    Search stays
                </button>
            </div>
        </form>
    </div>
</div>

{{-- FEATURED BUSINESSES SECTION --}}
<div class="bg-white">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Featured stays &amp; businesses</h2>
            <a href="{{ route('businesses.index') }}" class="text-sm text-sky-700 hover:underline">
                View all &rarr;
            </a>
        </div>

        @if($featuredBusinesses->isEmpty())
            <p class="text-gray-500">No approved businesses available yet. Please check back later.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredBusinesses as $business)
                    <a href="{{ route('businesses.show', $business->id) }}"
                       class="block bg-white shadow-sm border rounded-xl overflow-hidden hover:shadow-lg transition">

                        {{-- Business image --}}
                        <div class="h-40 w-full overflow-hidden">
                            @if(!empty($business->thumbnail))
                                <img
                                    src="{{ $business->thumbnail }}"
                                    alt="{{ $business->name }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <img
                                    src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80&sig={{ $business->id }}"
                                    alt="{{ $business->name }}"
                                    class="w-full h-full object-cover"
                                >
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $business->name }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ ucfirst($business->category) }}
                                @if($business->municipality)
                                    · {{ $business->municipality }}
                                @endif
                            </p>

                            @if($business->description)
                                <p class="mt-2 text-sm text-gray-700">
                                    {{ \Illuminate\Support\Str::limit($business->description, 120) }}
                                </p>
                            @endif

                            <p class="mt-3 text-sm text-gray-500">
                                @if($business->min_price && $business->max_price)
                                    From ₱{{ number_format($business->min_price) }} –
                                    ₱{{ number_format($business->max_price) }} per night
                                @elseif($business->min_price)
                                    From ₱{{ number_format($business->min_price) }} per night
                                @else
                                    Price range not specified
                                @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
