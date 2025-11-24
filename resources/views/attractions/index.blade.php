@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Attractions in Bantayan Island</h1>
            <p class="text-gray-600 text-sm">
                Discover beaches, churches, landmarks, and more around Bantayan, Santa Fe, and Madridejos.
            </p>
        </div>

        <a href="{{ route('home') }}" class="text-sm text-sky-700 hover:underline">
            ← Back to homepage
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('attractions.index') }}" class="bg-white shadow rounded p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                <input type="text" name="q" value="{{ $search ?? '' }}"
                       placeholder="Beach, church, landmark..."
                       class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Municipality</label>
                <select name="municipality" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Any</option>
                    @foreach($municipalities as $mun)
                        <option value="{{ $mun }}" @selected(($municipality ?? null) === $mun)>{{ $mun }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                <select name="category" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Any</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(($category ?? null) === $cat)>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2 text-sm">
            <button type="submit"
                    class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700">
                Apply filters
            </button>
            <a href="{{ route('attractions.index') }}"
               class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">
                Clear
            </a>
        </div>
    </form>

    @if($attractions->isEmpty())
        <div class="bg-white shadow rounded p-6 text-center text-gray-500">
            No attractions found.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($attractions as $attraction)
                <div class="bg-white shadow rounded overflow-hidden flex flex-col">
                    @if($attraction->thumbnail)
                        <img src="{{ asset('storage/'.$attraction->thumbnail) }}"
                             alt="{{ $attraction->name }}"
                             class="h-40 w-full object-cover">
                    @else
                        <div class="h-40 w-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                            No image
                        </div>
                    @endif

                    <div class="p-4 flex-1 flex flex-col">
                        <h2 class="font-semibold text-lg mb-1">
                            <a href="{{ route('attractions.show', $attraction->id) }}"
                               class="hover:text-sky-700">
                                {{ $attraction->name }}
                            </a>
                        </h2>
                        <p class="text-xs text-gray-500 mb-2">
                            {{ $attraction->municipality ?? 'Bantayan Island' }}
                            @if($attraction->category)
                                · {{ ucfirst($attraction->category) }}
                            @endif
                        </p>
                        <p class="text-sm text-gray-700 line-clamp-3 mb-3">
                            {{ \Illuminate\Support\Str::limit($attraction->description, 120) }}
                        </p>

                        <div class="mt-auto text-xs text-gray-500 space-y-1">
                            @if($attraction->opening_hours)
                                <div>🕒 {{ $attraction->opening_hours }}</div>
                            @endif
                            @if($attraction->entrance_fee)
                                <div>🎫 {{ $attraction->entrance_fee }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $attractions->links() }}
        </div>
    @endif
</div>
@endsection
