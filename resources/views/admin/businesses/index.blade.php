@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manage Businesses</h1>
            <p class="text-xs text-slate-500 mt-1">
                Review and approve registered tourism businesses across Bantayan Island.
            </p>
        </div>

        <form method="GET" class="flex items-center gap-2">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search businesses..."
                class="border rounded-lg px-3 py-1.5 text-sm"
            >
            <button class="px-3 py-1.5 text-sm rounded-lg bg-sky-600 text-white hover:bg-sky-700">
                Search
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-2 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($businesses->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 text-sm text-slate-600">
            No businesses found.
        </div>
    @else
        <div class="bg-white shadow-sm rounded-xl border border-slate-100 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase">
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Owner</th>
                        <th class="px-4 py-2">Municipality</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Created</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($businesses as $biz)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2">
                                <div class="font-semibold text-slate-900">
                                    {{ $biz->name }}
                                </div>
                                @if($biz->category)
                                    <div class="text-[11px] text-slate-500">
                                        {{ ucfirst($biz->category) }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                <div class="text-slate-900">
                                    {{ $biz->user->name ?? 'N/A' }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    {{ $biz->user->email ?? '' }}
                                </div>
                            </td>

                            <td class="px-4 py-2">
                                {{ $biz->municipality ?? '-' }}
                            </td>

                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold
                                    @if($biz->status === 'pending')
                                        bg-amber-100 text-amber-800
                                    @elseif($biz->status === 'approved')
                                        bg-emerald-100 text-emerald-800
                                    @elseif($biz->status === 'rejected')
                                        bg-rose-100 text-rose-800
                                    @else
                                        bg-slate-100 text-slate-700
                                    @endif
                                ">
                                    {{ ucfirst($biz->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-2 text-xs text-slate-500">
                                {{ $biz->created_at?->format('M d, Y') }}
                            </td>

                            <td class="px-4 py-2 text-right">
                                <div class="inline-flex gap-2">
                                    {{-- Review button opens modal --}}
                                    <button
                                        type="button"
                                        data-modal-target="review-modal-{{ $biz->id }}"
                                        data-modal-toggle="review-modal-{{ $biz->id }}"
                                        class="px-3 py-1.5 bg-sky-600 text-white text-xs rounded-lg hover:bg-sky-700">
                                        Review
                                    </button>

                                    {{-- Approve --}}
                                    <form action="{{ route('admin.businesses.approve', $biz->id) }}" method="POST">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="px-3 py-1.5 bg-emerald-600 text-white text-xs rounded-lg hover:bg-emerald-700"
                                            @if($biz->status === 'approved') disabled @endif>
                                            Approve
                                        </button>
                                    </form>

                                    {{-- Reject --}}
                                    <form action="{{ route('admin.businesses.reject', $biz->id) }}" method="POST">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="px-3 py-1.5 bg-rose-600 text-white text-xs rounded-lg hover:bg-rose-700"
                                            @if($biz->status === 'rejected') disabled @endif>
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal partial for detailed review --}}
                        @include('admin.businesses.partials.review-modal', ['business' => $biz])
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $businesses->links() }}
        </div>
    @endif
</div>
@endsection
