@extends('layouts.owner')

@section('content')
<div class="max-w-5xl mx-auto py-6">

    <h1 class="text-2xl font-bold mb-2">Business Gallery</h1>
    <p class="text-sm text-gray-600 mb-4">
        Upload photos to showcase your business on the tourism portal.
    </p>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Upload form --}}
    <div class="bg-white shadow rounded p-4 mb-6">
        <form action="{{ route('owner.business.images.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Upload Images
                </label>
                <input type="file" name="images[]" multiple
                       class="mt-1 w-full text-sm">
                <p class="text-xs text-gray-500 mt-1">
                    You can select multiple files. Max 4MB each.
                </p>
                @error('images.*')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="px-4 py-2 bg-sky-600 text-white rounded text-sm hover:bg-sky-700">
                Upload
            </button>
        </form>
    </div>

    {{-- Existing images --}}
    <div class="bg-white shadow rounded p-4">
        <h2 class="text-lg font-semibold mb-3">Current photos</h2>

        @if($images->isEmpty())
            <p class="text-sm text-gray-500">
                No images uploaded yet.
            </p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($images as $img)
                    <div class="relative group">
                        <img src="{{ asset('storage/'.$img->path) }}"
                             alt="Business photo"
                             class="w-full h-32 object-cover rounded">

                        <form action="{{ route('owner.business.images.destroy', $img->id) }}"
                              method="POST"
                              class="absolute top-1 right-1"
                              onsubmit="return confirm('Remove this image?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 text-white text-xs px-2 py-1 rounded opacity-80 hover:opacity-100">
                                ✕
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
