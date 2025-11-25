@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                Attractions in Bantayan Island
            </h1>
            <p class="text-sm text-slate-600 mt-1">
                Discover beaches, churches, landmarks and more across Bantayan, Santa Fe, and Madridejos.
            </p>
        </div>

        <a href="{{ route('home') }}"
           class="text-xs text-sky-700 hover:text-sky-900">
            ← Back to homepage
        </a>
    </div>

    {{-- FILTER CARD --}}
    <form method="GET" action="{{ route('attractions.index') }}" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 md:p-5 space-y-4 text-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search --}}
                <div class="md:col-span-1">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Search attractions
                    </label>
                    <input type="text"
                           name="q"
                           value="{{ $search ?? '' }}"
                           placeholder="Beach, church, landmark..."
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
                            <option value="{{ $mun }}" @selected(($municipality ?? null) === $mun)>{{ $mun }}</option>
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
                        <option value="">Any</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected(($category ?? null) === $cat)>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="submit"
                        class="px-4 py-2 bg-sky-600 text-white rounded-lg text-sm font-medium hover:bg-sky-700">
                    Apply filters
                </button>
                <a href="{{ route('attractions.index') }}"
                   class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-50">
                    Clear
                </a>
            </div>

            @if($search || ($municipality ?? null) || ($category ?? null))
                <div class="pt-2 border-t text-xs text-slate-500 flex flex-wrap gap-2">
                    <span class="font-semibold text-slate-600">Active filters:</span>
                    @if($search)
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">
                            “{{ $search }}”
                        </span>
                    @endif
                    @if($municipality ?? null)
                        <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700">
                            {{ $municipality }}
                        </span>
                    @endif
                    @if($category ?? null)
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                            {{ ucfirst($category) }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </form>

    {{-- RESULTS --}}
    @if($attractions->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-10 text-center text-slate-500 text-sm">
            No attractions found. Try a different keyword or remove some filters.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($attractions as $attraction)
                <a href="{{ route('attractions.show', $attraction->id) }}"
                   class="group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col hover:-translate-y-1 hover:shadow-md transition">
                    {{-- Image --}}
                    @if($attraction->thumbnail)
                        <img src="{{ asset('storage/'.$attraction->thumbnail) }}"
                             alt="{{ $attraction->name }}"
                             class="h-40 w-full object-cover group-hover:scale-[1.02] transition-transform">
                    @else
                        <div class="h-40 w-full bg-slate-200 flex items-center justify-center text-xs text-slate-500">
                            No image available
                        </div>
                    @endif

                    {{-- Content --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h2 class="font-semibold text-base group-hover:text-sky-700">
                                {{ $attraction->name }}
                            </h2>
                            @if($attraction->category)
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ ucfirst($attraction->category) }}
                                </span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-500 mb-2">
                            {{ $attraction->municipality ?? 'Bantayan Island' }}
                            @if($attraction->address)
                                · {{ $attraction->address }}
                            @endif
                        </p>

                        <p class="text-sm text-slate-700 mb-3">
                            {{ \Illuminate\Support\Str::limit($attraction->description, 110) }}
                        </p>

                        <div class="mt-auto pt-2 border-t border-slate-100 text-xs text-slate-500 space-y-1">
                            @if($attraction->opening_hours)
                                <div>🕒 {{ $attraction->opening_hours }}</div>
                            @endif
                            @if($attraction->entrance_fee)
                                <div>🎫 {{ $attraction->entrance_fee }}</div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex items-center justify-between text-xs text-slate-500">
            <div>
                Showing
                <span class="font-semibold">{{ $attractions->firstItem() }}</span>
                –
                <span class="font-semibold">{{ $attractions->lastItem() }}</span>
                of
                <span class="font-semibold">{{ $attractions->total() }}</span>
                attractions
            </div>
            <div>
                {{ $attractions->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
