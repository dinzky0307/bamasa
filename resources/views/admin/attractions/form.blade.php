@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">
        {{ $mode === 'create' ? 'Add Attraction' : 'Edit Attraction' }}
    </h1>

    @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-800 p-3 rounded text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ $mode === 'create'
                    ? route('admin.attractions.store')
                    : route('admin.attractions.update', $attraction->id) }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white shadow rounded p-6 space-y-4"
    >
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <div>
            <label class="block text-sm font-semibold text-gray-700">Name</label>
            <input type="text" name="name" class="mt-1 w-full border rounded px-3 py-2 text-sm"
                   value="{{ old('name', $attraction->name) }}" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Municipality</label>
                <select name="municipality" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select</option>
                    @foreach($municipalities as $mun)
                        <option value="{{ $mun }}" @selected(old('municipality', $attraction->municipality) === $mun)>
                            {{ $mun }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Category</label>
                <select name="category" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(old('category', $attraction->category) === $cat)>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Address</label>
            <input type="text" name="address" class="mt-1 w-full border rounded px-3 py-2 text-sm"
                   value="{{ old('address', $attraction->address) }}">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Description</label>
            <textarea name="description" rows="4"
                      class="mt-1 w-full border rounded px-3 py-2 text-sm">{{ old('description', $attraction->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Latitude</label>
                <input type="text" name="latitude" class="mt-1 w-full border rounded px-3 py-2 text-sm"
                       value="{{ old('latitude', $attraction->latitude) }}">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Longitude</label>
                <input type="text" name="longitude" class="mt-1 w-full border rounded px-3 py-2 text-sm"
                       value="{{ old('longitude', $attraction->longitude) }}">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Status</label>
                <select name="status" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                    <option value="published" @selected(old('status', $attraction->status) === 'published')>Published</option>
                    <option value="draft" @selected(old('status', $attraction->status) === 'draft')>Draft</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Opening Hours</label>
                <input type="text" name="opening_hours" class="mt-1 w-full border rounded px-3 py-2 text-sm"
                       value="{{ old('opening_hours', $attraction->opening_hours) }}">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Entrance Fee</label>
                <input type="text" name="entrance_fee" class="mt-1 w-full border rounded px-3 py-2 text-sm"
                       value="{{ old('entrance_fee', $attraction->entrance_fee) }}">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Thumbnail</label>
                <input type="file" name="thumbnail" class="mt-1 w-full text-sm">
                <p class="text-xs text-gray-500 mt-1">JPEG/PNG, max 2MB.</p>
            </div>
            <div class="flex justify-center">
                @if($attraction->thumbnail)
                    <img src="{{ asset('storage/'.$attraction->thumbnail) }}"
                         alt="Current thumbnail"
                         class="w-32 h-32 object-cover rounded shadow">
                @else
                    <div class="w-32 h-32 flex items-center justify-center bg-gray-100 rounded text-xs text-gray-500">
                        No image yet
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-2">
            <a href="{{ route('admin.attractions') }}"
               class="px-4 py-2 border rounded text-sm text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-sky-600 text-white rounded text-sm hover:bg-sky-700">
                {{ $mode === 'create' ? 'Create Attraction' : 'Save Changes' }}
            </button>
        </div>
    </form>
</div>
@endsection
