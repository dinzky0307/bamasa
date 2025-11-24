@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">

    <a href="{{ route('attractions.index') }}"
       class="text-sm text-sky-700 hover:underline">
        ← Back to attractions
    </a>

    <div class="mt-4 bg-white shadow rounded overflow-hidden">
        @if($attraction->thumbnail)
            <img src="{{ asset('storage/'.$attraction->thumbnail) }}"
                 alt="{{ $attraction->name }}"
                 class="w-full h-64 object-cover">
        @endif

        <div class="p-6">
            <h1 class="text-3xl font-bold mb-2">{{ $attraction->name }}</h1>

            <p class="text-sm text-gray-500 mb-4">
                {{ $attraction->municipality ?? 'Bantayan Island' }}
                @if($attraction->address)
                    · {{ $attraction->address }}
                @endif
                @if($attraction->category)
                    · {{ ucfirst($attraction->category) }}
                @endif
            </p>

            @if($attraction->opening_hours || $attraction->entrance_fee)
                <div class="mb-4 text-sm text-gray-700 space-y-1">
                    @if($attraction->opening_hours)
                        <div>🕒 <span class="font-semibold">Opening hours:</span> {{ $attraction->opening_hours }}</div>
                    @endif
                    @if($attraction->entrance_fee)
                        <div>🎫 <span class="font-semibold">Entrance fee:</span> {{ $attraction->entrance_fee }}</div>
                    @endif
                </div>
            @endif

            <div class="prose max-w-none text-gray-800 text-sm leading-relaxed">
                {!! nl2br(e($attraction->description)) !!}
            </div>

            @if($attraction->latitude && $attraction->longitude)
                <div class="mt-6 text-xs text-gray-500">
                    Coordinates: {{ $attraction->latitude }}, {{ $attraction->longitude }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
