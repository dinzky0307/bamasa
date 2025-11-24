@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Manage Attractions</h1>

        <a href="{{ route('admin.attractions.create') }}"
           class="px-4 py-2 bg-sky-600 text-white rounded text-sm hover:bg-sky-700">
            + Add Attraction
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($attractions->isEmpty())
        <p class="text-gray-500">No attractions found.</p>
    @else
        <div class="bg-white shadow rounded overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Municipality</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Created</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($attractions as $att)
                        <tr>
                            <td class="px-4 py-2">{{ $att->name }}</td>
                            <td class="px-4 py-2">{{ $att->municipality ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $att->category ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="text-xs px-2 py-1 rounded
                                    @if($att->status === 'published') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($att->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $att->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-2 text-right">
                                <a href="{{ route('admin.attractions.edit', $att->id) }}"
                                   class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Edit
                                </a>
                                <form action="{{ route('admin.attractions.delete', $att->id) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Delete this attraction?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
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
