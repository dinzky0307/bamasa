@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Island Info & Announcements</h1>
            <p class="text-xs text-slate-500 mt-1">
                Publish educational information, advisories and updates about Bantayan Island.
            </p>
        </div>

        <a href="{{ route('admin.announcements.create') }}"
           class="inline-flex items-center px-3 py-1.5 rounded-lg bg-sky-600 text-white text-sm hover:bg-sky-700">
            + New Article
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-2 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($announcements->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 text-sm text-slate-600">
            No announcements yet.
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase">
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Scope</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Published</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($announcements as $post)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2">
                                <div class="font-semibold text-slate-900">
                                    {{ $post->title }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    {{ $post->excerpt }}
                                </div>
                            </td>
                            <td class="px-4 py-2 text-xs text-slate-600">
                                {{ $post->municipality_scope ?? 'Island-wide' }}
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold
                                    @if($post->status === 'published')
                                        bg-emerald-100 text-emerald-800
                                    @else
                                        bg-slate-100 text-slate-700
                                    @endif
                                ">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-xs text-slate-500">
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-4 py-2 text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.announcements.edit', $post->id) }}"
                                       class="px-3 py-1.5 text-xs rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $post->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this announcement?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1.5 text-xs rounded-lg bg-rose-600 text-white hover:bg-rose-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
