@extends('layouts.owner')

@section('content')
<h1 class="text-2xl font-bold mb-4">Step 1: Business Details</h1>

@if ($errors->any())
    <div class="mb-4 bg-red-100 text-red-800 p-3 rounded text-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('owner.wizard.step1') }}" method="POST"
      class="space-y-4 bg-white p-6 rounded shadow">
    @csrf

    <div>
        <label class="font-semibold block">Business Name</label>
        <input name="name" type="text" class="w-full border rounded p-2"
               value="{{ old('name', $business->name) }}" required>
    </div>

    <div>
        <label class="font-semibold block">Category</label>
        <select name="category" class="w-full border rounded p-2" required>
            @foreach(['hotel','resort','homestay','restaurant','tour_operator'] as $cat)
                <option value="{{ $cat }}" @selected(old('category', $business->category) === $cat)>
                    {{ ucfirst(str_replace('_',' ',$cat)) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="font-semibold block">Municipality</label>
        <select name="municipality" class="w-full border rounded p-2" required>
            @foreach(['Bantayan','Santa Fe','Madridejos'] as $m)
                <option value="{{ $m }}" @selected(old('municipality', $business->municipality) === $m)>
                    {{ $m }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="font-semibold block">Description</label>
        <textarea name="description" class="w-full border rounded p-2" rows="4" required>
{{ old('description', $business->description) }}</textarea>
    </div>

    <button type="submit"  {{-- 👈 important --}}
            class="bg-sky-600 text-white px-4 py-2 rounded">
        Next →
    </button>
</form>
@endsection
