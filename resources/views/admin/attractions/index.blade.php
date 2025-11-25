@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Manage Attractions
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                View and manage registered tourist attractions in Bantayan Island.
            </p>
        </div>

        <form method="GET" class="flex items-center gap-2">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   class="border rounded-lg px-3 py-1.5 text-sm"
                   placeholder="Search attractions...">
            <button class="px-3 py-1.5 text-sm rounded-lg bg-sky-600 text-white hover:bg-sky-700">
                Search
            </button>
        </form>
    </div>

    @if($attractions->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 text-sm text-slate-600">
            No attractions found.
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase">
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Municipality</th>
                        <th class="px-4 py-2">Category</th>
                        <th class="px-4 py-2">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($attractions as $attr)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2">
                                {{ $attr->name }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $attr->municipality ?? '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $attr->category ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-xs text-slate-500">
                                {{ $attr->created_at?->format('M d, Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attractions->links() }}
        </div>
    @endif
</div>
@endsection
