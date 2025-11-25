@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <p class="text-[11px] uppercase tracking-wide text-sky-600 font-semibold mb-1">
        {{ $announcement->municipality_scope ?? 'Island-wide' }}
    </p>

    <h1 class="text-3xl font-bold text-slate-900 mb-2">
        {{ $announcement->title }}
    </h1>

@if($announcement->image_path)
    <img src="{{ asset('storage/'.$announcement->image_path) }}"
         alt="{{ $announcement->title }}"
         class="w-full max-h-80 object-cover rounded-xl mb-6">
@endif


    <p class="text-xs text-slate-500 mb-6">
        {{ $announcement->published_at ? $announcement->published_at->format('F d, Y') : '' }}
        @if($announcement->user)
            · Posted by {{ $announcement->user->name }}
        @endif
    </p>

    <div class="prose prose-sm max-w-none">
        {!! nl2br(e($announcement->body)) !!}
    </div>

    <div class="mt-8">
        <a href="{{ route('announcements.index') }}" class="text-sm text-sky-600 hover:text-sky-800">
            ← Back to island information
        </a>
    </div>
</div>
@endsection
