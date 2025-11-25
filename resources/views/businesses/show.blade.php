@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Back link --}}
    <div class="mb-4 text-sm">
        <a href="{{ route('businesses.index') }}" class="text-sky-700 hover:text-sky-900">
            ← Back to all places
        </a>
    </div>

    {{-- HERO SECTION --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
            {{-- Main photo --}}
            <div class="md:col-span-2 relative">
                @if($business->thumbnail)
                    <img src="{{ asset('storage/'.$business->thumbnail) }}"
                         alt="{{ $business->name }}"
                         class="w-full h-64 md:h-full object-cover">
                @else
                    <div class="w-full h-64 md:h-full bg-slate-200 flex items-center justify-center text-sm text-slate-500">
                        No main photo available
                    </div>
                @endif

                {{-- Status badge --}}
                <div class="absolute top-3 left-3">
                    <span class="px-2.5 py-1 text-xs rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                        {{ ucfirst($business->status) }}
                    </span>
                </div>
            </div>

            {{-- Summary card --}}
            <div class="p-4 md:p-5 flex flex-col justify-between border-t md:border-t-0 md:border-l border-slate-100">
                <div class="space-y-2">
                    <h1 class="text-xl md:text-2xl font-bold leading-tight">
                        {{ $business->name }}
                    </h1>

                    <p class="text-xs text-slate-500">
                        {{ $business->municipality ?? 'Bantayan Island' }}
                        @if($business->address)
                            · {{ $business->address }}
                        @endif
                    </p>

                    <p class="text-xs text-slate-500">
                        {{ ucfirst(str_replace('_', ' ', $business->category)) }}
                    </p>

                    @if($business->min_price || $business->max_price)
                        <div class="mt-2">
                            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">
                                Estimated price range
                            </p>
                            <p class="text-lg font-semibold text-sky-700">
                                @if($business->min_price && $business->max_price)
                                    ₱{{ number_format($business->min_price) }} – ₱{{ number_format($business->max_price) }}
                                @elseif($business->min_price)
                                    From ₱{{ number_format($business->min_price) }}
                                @else
                                    Up to ₱{{ number_format($business->max_price) }}
                                @endif
                            </p>
                            <p class="text-[11px] text-slate-500">
                                Final rates are confirmed by the business upon booking.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Booking button --}}
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-2">
                    @auth
                        @if(auth()->user()->role === 'tourist')
                            <a href="{{ route('bookings.create', $business->id) }}"
                               class="w-full inline-flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium py-2.5 rounded-lg shadow-sm">
                                📅 Request a booking
                            </a>
                        @else
                            <p class="text-xs text-slate-500">
                                Logged in as {{ auth()->user()->role }}. Switch to a tourist account to book.
                            </p>
                        @endif
                    @endauth

                    @guest
                        <a href="{{ route('login') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium py-2.5 rounded-lg shadow-sm">
                            🔐 Login to book this place
                        </a>
                        <p class="text-[11px] text-slate-500 text-center">
                            Don’t have an account?
                            <a href="{{ route('register') }}" class="text-sky-700 hover:text-sky-900">
                                Sign up
                            </a>
                        </p>
                    @endguest>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Description + Gallery --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Description --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h2 class="text-lg font-semibold mb-2">About this place</h2>
                <div class="text-sm text-slate-700 leading-relaxed">
                    {!! nl2br(e($business->description)) !!}
                </div>
            </section>

            {{-- Gallery --}}
            @php
                $images = $business->relationLoaded('images') ? $business->images : ($business->images ?? collect());
            @endphp

            @if($images->isNotEmpty())
                <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold">Gallery</h2>
                        <p class="text-xs text-slate-500">
                            {{ $images->count() }} photo{{ $images->count() > 1 ? 's' : '' }} from this business
                        </p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($images as $img)
                            <div class="relative group">
                                <img src="{{ asset('storage/'.$img->path) }}"
                                     alt="Photo of {{ $business->name }}"
                                     class="w-full h-28 md:h-32 lg:h-36 object-cover rounded-lg shadow-sm group-hover:shadow-md group-hover:-translate-y-0.5 transition">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Contact / Info --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h2 class="text-lg font-semibold mb-3">Contact & information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-700">
                    <div class="space-y-2">
                        @if($business->phone)
                            <p>
                                <span class="font-semibold">📞 Phone:</span>
                                <span class="ml-1">{{ $business->phone }}</span>
                            </p>
                        @endif

                        @if($business->email)
                            <p>
                                <span class="font-semibold">✉️ Email:</span>
                                <span class="ml-1">{{ $business->email }}</span>
                            </p>
                        @endif

                        @if($business->website)
                            <p>
                                <span class="font-semibold">🌐 Website:</span>
                                <a href="{{ $business->website }}" target="_blank" class="ml-1 text-sky-700 hover:text-sky-900">
                                    Visit site ↗
                                </a>
                            </p>
                        @endif

                        @if($business->facebook_page)
                            <p>
                                <span class="font-semibold">📘 Facebook:</span>
                                <a href="{{ $business->facebook_page }}" target="_blank" class="ml-1 text-sky-700 hover:text-sky-900">
                                    View page ↗
                                </a>
                            </p>
                        @endif
                    </div>

                    <div class="space-y-2 text-sm">
                        <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Location</p>
                        <p>
                            {{ $business->name }}<br>
                            @if($business->address)
                                {{ $business->address }}<br>
                            @endif
                            {{ $business->municipality ?? 'Bantayan Island' }}, Cebu
                        </p>

                        @if($business->latitude ?? false || $business->longitude ?? false)
                            <p class="text-xs text-slate-500 mt-2">
                                Coordinates:
                                @if($business->latitude) {{ $business->latitude }} @endif
                                @if($business->longitude) , {{ $business->longitude }} @endif
                            </p>
                        @endif
                    </div>
                </div>
            </section>
        </div>

        {{-- Right: Availability --}}
        <aside class="space-y-6">
            @php
                $today        = \Carbon\Carbon::today();
                $firstOfMonth = $currentMonth ?? $today->copy()->startOfMonth();
                $year         = $firstOfMonth->year;
                $month        = $firstOfMonth->month;
                $daysInMonth  = $firstOfMonth->daysInMonth;
                $startWeekday = $firstOfMonth->dayOfWeekIso; // 1..7

                $unavailableSet = $unavailableDates ?? [];
                $prev = $prevMonth ?? $firstOfMonth->copy()->subMonth();
                $next = $nextMonth ?? $firstOfMonth->copy()->addMonth();
            @endphp

            <section class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                <div class="flex items-center justify-between mb-2">
                    <a href="{{ route('businesses.show', ['business' => $business->id, 'month' => $prev->format('Y-m')]) }}"
                       class="text-[11px] text-sky-700 hover:text-sky-900">
                        ← {{ $prev->format('M Y') }}
                    </a>

                    <h2 class="text-sm font-semibold">
                        Availability ({{ $firstOfMonth->format('F Y') }})
                    </h2>

                    <a href="{{ route('businesses.show', ['business' => $business->id, 'month' => $next->format('Y-m')]) }}"
                       class="text-[11px] text-sky-700 hover:text-sky-900">
                        {{ $next->format('M Y') }} →
                    </a>
                </div>

                <div class="text-[11px] text-slate-500 mb-2 flex flex-wrap gap-2">
                    <span class="inline-flex items-center">
                        <span class="w-3 h-3 inline-block bg-gray-100 border border-gray-300 mr-1"></span> Available
                    </span>
                    <span class="inline-flex items-center">
                        <span class="w-3 h-3 inline-block bg-red-200 border border-red-400 mr-1"></span> Booked
                    </span>
                    <span class="inline-flex items-center">
                        <span class="w-3 h-3 inline-block bg-sky-200 border border-sky-500 mr-1"></span> Today
                    </span>
                </div>

                {{-- Weekday headers --}}
                <div class="grid grid-cols-7 text-center text-[11px] font-semibold text-slate-600 mb-1">
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                    <div>Sun</div>
                </div>

                {{-- Calendar grid --}}
                <div class="grid grid-cols-7 text-center text-xs gap-px bg-slate-200 rounded overflow-hidden">
                    @php
                        $cellCount = 0;
                    @endphp

                    {{-- Empty cells before day 1 --}}
                    @for($i = 1; $i < $startWeekday; $i++)
                        @php $cellCount++; @endphp
                        <div class="bg-slate-50 h-7"></div>
                    @endfor

                    {{-- Actual days --}}
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $cellCount++;
                            $currentDate  = \Carbon\Carbon::create($year, $month, $day)->toDateString();
                            $isUnavailable = in_array($currentDate, $unavailableSet);
                            $isToday       = $currentDate === $today->toDateString();
                        @endphp

                        <div class="
                            h-7 flex items-center justify-center border
                            @if($isUnavailable)
                                bg-red-200 border-red-400 text-red-800
                            @elseif($isToday)
                                bg-sky-200 border-sky-500 font-semibold
                            @else
                                bg-slate-50 border-slate-200
                            @endif
                        ">
                            {{ $day }}
                        </div>
                    @endfor

                    {{-- Fill last row --}}
                    @while($cellCount % 7 !== 0)
                        @php $cellCount++; @endphp
                        <div class="bg-slate-50 h-7"></div>
                    @endwhile
                </div>

                <p class="mt-2 text-[11px] text-slate-500">
                    * Availability is based on existing bookings recorded in the system.
                    For specific questions, please contact the business directly.
                </p>
            </section>
        </aside>
    </div>
</div>
@endsection
