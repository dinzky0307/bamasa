@extends('layouts.owner')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-4">My Business Profile</h1>

    @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 text-red-800 p-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-800 p-3 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.business.update') }}" method="POST" enctype="multipart/form-data"
          class="bg-white shadow rounded p-6 space-y-4">
        @csrf

        {{-- Name & Category --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Business Name</label>
                <input type="text" name="name" value="{{ old('name', $business->name) }}"
                       class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Category</label>
                <select name="category" class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
                    @php
                        $categories = ['hotel', 'resort', 'homestay', 'restaurant', 'tour_operator'];
                    @endphp
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $business->category) === $cat ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $cat)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700">Description</label>
            <textarea name="description" rows="4"
                      class="mt-1 w-full border rounded px-3 py-2 text-sm">{{ old('description', $business->description) }}</textarea>
        </div>

        {{-- Address & Municipality --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Address</label>
                <input type="text" name="address" value="{{ old('address', $business->address) }}"
                       class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Municipality</label>
                <select name="municipality" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                    @php
                        $municipalities = ['Bantayan', 'Santa Fe', 'Madridejos'];
                    @endphp
                    <option value="">Select municipality</option>
                    @foreach($municipalities as $mun)
                        <option value="{{ $mun }}" {{ old('municipality', $business->municipality) === $mun ? 'selected' : '' }}>
                            {{ $mun }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Contact --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $business->phone) }}"
                       class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $business->email) }}"
                       class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Website</label>
                <input type="url" name="website" value="{{ old('website', $business->website) }}"
                       class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>

        {{-- Social --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700">Facebook Page URL</label>
            <input type="url" name="facebook_page"
                   value="{{ old('facebook_page', $business->facebook_page) }}"
                   class="mt-1 w-full border rounded px-3 py-2 text-sm">
        </div>

        {{-- Pricing --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Minimum Price (₱)</label>
                <input type="number" name="min_price"
                       value="{{ old('min_price', $business->min_price) }}"
                       class="mt-1 w-full border rounded px-3 py-2 text-sm" min="0" step="1">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Maximum Price (₱)</label>
                <input type="number" name="max_price"
                       value="{{ old('max_price', $business->max_price) }}"
                       class="mt-1 w-full border rounded px-3 py-2 text-sm" min="0" step="1">
            </div>
        </div>

        {{-- Thumbnail --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Thumbnail Image</label>
                <input type="file" name="thumbnail"
                       class="mt-1 w-full text-sm">
                <p class="text-xs text-gray-500 mt-1">JPEG/PNG up to 2MB.</p>
            </div>

            <div class="flex justify-center">
                @if($business->thumbnail)
                    <img src="{{ asset('storage/'.$business->thumbnail) }}"
                         alt="Current thumbnail"
                         class="w-32 h-32 object-cover rounded shadow">
                @else
                    <div class="w-32 h-32 flex items-center justify-center bg-gray-100 rounded text-xs text-gray-500">
                        No thumbnail yet
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit"
                    class="px-5 py-2 bg-sky-600 text-white rounded hover:bg-sky-700 text-sm font-semibold">
                Save Changes
            </button>
        </div>

    </form>
</div>
@endsection
