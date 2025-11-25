@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-slate-900">Island Information & Updates</h1>
        <p class="text-sm text-slate-500 mt-2">
            Travel advisories, cultural insights and helpful information for exploring Bantayan Island.
        </p>
    </div>

    @if($announcements->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 text-sm text-slate-600">
            No posts yet. Please check back soon.
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($announcements as $post)
                <a href="{{ route('announcements.show', $post->id) }}"
                   class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 hover:shadow-md transition-shadow">
                    
                   @if($post->image_path)
    <img src="{{ asset('storage/'.$post->image_path) }}"
         alt="{{ $post->title }}"
         class="w-full h-40 object-cover rounded-lg mb-3">
@endif

                   
                   
                   <p class="text-[11px] uppercase tracking-wide text-sky-600 font-semibold mb-1">
                        {{ $post->municipality_scope ?? 'Island-wide' }}
                    </p>
                    <h2 class="text-lg font-semibold text-slate-900 mb-1">
                        {{ $post->title }}
                    </h2>
                    <p class="text-xs text-slate-500 mb-2">
                        {{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}
                    </p>
                    <p class="text-sm text-slate-700 line-clamp-3">
                        {{ $post->excerpt }}
                    </p>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
