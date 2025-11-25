@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">
                My trips
            </p>
            <h1 class="text-2xl md:text-3xl font-bold leading-tight">
                My bookings
            </h1>
            <p class="text-sm text-slate-600 mt-1">
                View all your past and upcoming reservations in Bantayan Island.
            </p>
        </div>

        <a href="{{ route('businesses.index') }}"
           class="text-xs px-3 py-2 rounded-full border border-slate-300 text-slate-700 hover:bg-slate-50 inline-flex items-center gap-1">
            <span>Browse more places</span> <span>↗</span>
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
            <p class="text-sm text-slate-700 mb-2">
                You don’t have any bookings yet.
            </p>
            <p class="text-xs text-slate-500 mb-4">
                Start exploring places to stay and local restaurants around Bantayan Island.
            </p>
            <a href="{{ route('businesses.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-medium hover:bg-sky-700">
                📍 Find a place to stay
            </a>
        </div>
    @else
        @php
            $today = \Carbon\Carbon::today();
            $upcoming = $bookings->filter(function($b) use ($today) {
                return $b->check_in && $b->check_in->isFuture();
            });
            $past = $bookings->filter(function($b) use ($today) {
                return $b->check_out && $b->check_out->lt($today);
            });
            $current = $bookings->filter(function($b) use ($today) {
                return $b->check_in && $b->check_out &&
                       $b->check_in->lte($today) && $b->check_out->gte($today);
            });
        @endphp

        {{-- Tabs (just visual sections, no JS needed) --}}
        <div class="space-y-6">

            {{-- Current / upcoming first --}}
            @if($current->isNotEmpty())
                <section>
                    <h2 class="text-sm font-semibold text-slate-800 mb-2">
                        Ongoing stay
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($current as $booking)
                            @include('bookings.partials.booking-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($upcoming->isNotEmpty())
                <section>
                    <h2 class="text-sm font-semibold text-slate-800 mb-2">
                        Upcoming bookings
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($upcoming as $booking)
                            @include('bookings.partials.booking-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Past --}}
            @if($past->isNotEmpty())
                <section>
                    <h2 class="text-sm font-semibold text-slate-800 mb-2">
                        Past trips
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($past as $booking)
                            @include('bookings.partials.booking-card', ['booking' => $booking, 'isPast' => true])
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    @endif
</div>
@endsection
