@extends('layouts.app')

@section('content')
{{-- HERO SECTION --}}
<section class="relative overflow-hidden">
    {{-- Background image placeholder --}}
    <div class="absolute inset-0">
        <div class="w-full h-full bg-[url('https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg')] bg-cover bg-center brightness-75">
        </div>
    </div>

    {{-- Gradient overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/40 to-slate-900/20"></div>

    <div class="relative max-w-6xl mx-auto px-4 py-16 md:py-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            {{-- Left: text --}}
            <div class="text-white space-y-5">
                <p class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-xs uppercase tracking-wide border border-white/20">
                    🌴 Welcome to Bantayan Island · Cebu
                </p>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
                    Discover white-sand beaches,<br class="hidden md:block">
                    cozy stays & local flavors.
                </h1>

                <p class="text-sm md:text-base text-slate-100 max-w-xl">
                    Plan your trip across Bantayan, Santa Fe, and Madridejos in one place.
                    Browse verified accommodations, attractions, and experiences.
                </p>

                {{-- Quick stats (static for now) --}}
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold">3</span>
                        <span class="text-slate-200 text-xs">Municipalities</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold">Stays & Dining</span>
                        <span class="text-slate-200 text-xs">Resorts · Homestays · Restaurants</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold">Attractions</span>
                        <span class="text-slate-200 text-xs">Beaches · Churches · Landmarks</span>
                    </div>
                </div>
            </div>

            {{-- Right: search card --}}
            <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl p-5 md:p-6 space-y-4">
                <h2 class="text-lg font-semibold text-slate-800">
                    Find a place to stay
                </h2>
                <p class="text-xs text-slate-500">
                    Search accommodations and restaurants across the island.
                </p>

                <form action="{{ route('businesses.index') }}" method="GET" class="space-y-3 text-sm">
                    {{-- Where --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            Where do you want to stay?
                        </label>
                        <input
                            type="text"
                            name="q"
                            placeholder="Resort name, homestay, restaurant..."
                            class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm"
                        >
                    </div>

                    {{-- Municipality + dates --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Municipality
                            </label>
                            <select
                                name="municipality"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                            >
                                <option value="">Anywhere in Bantayan Island</option>
                                <option value="Santa Fe">Santa Fe</option>
                                <option value="Bantayan">Bantayan</option>
                                <option value="Madridejos">Madridejos</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Price range (₱)
                            </label>
                            <div class="flex gap-2">
                                <input
                                    type="number" name="min_price" min="0"
                                    placeholder="Min"
                                    class="w-1/2 border rounded-lg px-2 py-2 text-xs focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                >
                                <input
                                    type="number" name="max_price" min="0"
                                    placeholder="Max"
                                    class="w-1/2 border rounded-lg px-2 py-2 text-xs focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                >
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full mt-2 flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium py-2.5 rounded-lg shadow-sm">
                        🔍 Search places
                    </button>
                </form>

                <div class="pt-2 border-t text-[11px] text-slate-500">
                    Looking for things to do?
                    <a href="{{ route('attractions.index') }}" class="text-sky-600 hover:underline">
                        Browse attractions →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION: Explore by municipality --}}
<section class="max-w-6xl mx-auto px-4 py-10 md:py-14">
    <h2 class="text-xl md:text-2xl font-semibold mb-2">
        Explore Bantayan Island by municipality
    </h2>
    <p class="text-sm text-slate-600 mb-6">
        Each town offers its own character, beaches, and local experiences.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-sm">
        {{-- Santa Fe --}}
        <a href="{{ route('businesses.index', ['municipality' => 'Santa Fe']) }}"
           class="group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:-translate-y-1 hover:shadow-md transition">
            <div class="h-32 bg-[url('https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg')] bg-cover bg-center group-hover:scale-[1.02] transition-transform"></div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-semibold">Santa Fe</h3>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-100">
                        Beachfront stays
                    </span>
                </div>
                <p class="text-xs text-slate-600">
                    Famous for powdery white-sand beaches and island-hopping spots.
                </p>
            </div>
        </a>

        {{-- Bantayan --}}
        <a href="{{ route('businesses.index', ['municipality' => 'Bantayan']) }}"
           class="group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:-translate-y-1 hover:shadow-md transition">
            <div class="h-32 bg-[url('https://images.pexels.com/photos/258154/pexels-photo-258154.jpeg')] bg-cover bg-center group-hover:scale-[1.02] transition-transform"></div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-semibold">Bantayan</h3>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                        Heritage & markets
                    </span>
                </div>
                <p class="text-xs text-slate-600">
                    Explore the old church, town plaza, and local seafood markets.
                </p>
            </div>
        </a>

        {{-- Madridejos --}}
        <a href="{{ route('businesses.index', ['municipality' => 'Madridejos']) }}"
           class="group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:-translate-y-1 hover:shadow-md transition">
            <div class="h-32 bg-[url('https://images.pexels.com/photos/237272/pexels-photo-237272.jpeg')] bg-cover bg-center group-hover:scale-[1.02] transition-transform"></div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-semibold">Madridejos</h3>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                        Quiet escapes
                    </span>
                </div>
                <p class="text-xs text-slate-600">
                    A calmer side of the island with peaceful coastal views.
                </p>
            </div>
        </a>
    </div>
</section>

{{-- SECTION: What can tourists do here? --}}
<section class="bg-slate-900 text-slate-50">
    <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <h2 class="text-xl md:text-2xl font-semibold mb-3">
                Plan your trip in one portal
            </h2>
            <p class="text-sm text-slate-300 mb-4">
                From booking where to stay to discovering must-see attractions, the Bantayan Island Tourism Portal
                gives you a single place to research your visit.
            </p>
            <a href="{{ route('businesses.index') }}"
               class="inline-flex items-center text-sm text-sky-300 hover:text-sky-200">
                Start browsing places to stay →
            </a>
        </div>

        <div class="space-y-4 text-sm">
            <div class="flex gap-3">
                <div class="mt-1 text-lg">🏨</div>
                <div>
                    <div class="font-semibold">Book verified local stays</div>
                    <p class="text-slate-300 text-xs">
                        Find accommodations and restaurants registered with the LGUs of Bantayan, Santa Fe, and Madridejos.
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="mt-1 text-lg">📍</div>
                <div>
                    <div class="font-semibold">Discover attractions</div>
                    <p class="text-slate-300 text-xs">
                        Browse beaches, churches, landmarks and more through the attractions directory.
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="mt-1 text-lg">📅</div>
                <div>
                    <div class="font-semibold">Check availability before you go</div>
                    <p class="text-slate-300 text-xs">
                        View availability calendars and request bookings directly from local businesses.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-4 text-sm">
            <div class="flex gap-3">
                <div class="mt-1 text-lg">🛶</div>
                <div>
                    <div class="font-semibold">Island-hopping & activities</div>
                    <p class="text-slate-300 text-xs">
                        Use the portal as a starting point to coordinate tours, island-hopping, and other experiences.
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="mt-1 text-lg">🧭</div>
                <div>
                    <div class="font-semibold">Plan by municipality</div>
                    <p class="text-slate-300 text-xs">
                        Decide where to base your stay depending on the experiences you want—beachfront, heritage, or quiet escape.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
