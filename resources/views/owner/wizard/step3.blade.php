@extends('layouts.owner')

@section('content')
<h1 class="text-2xl font-bold mb-4">Step 3: Pricing</h1>

<form action="{{ route('owner.wizard.step3') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
    @csrf

    <div>
        <label class="font-semibold block">Minimum Price (₱)</label>
        <input name="min_price" type="number" class="w-full border rounded p-2"
               value="{{ old('min_price', $business->min_price) }}" required>
    </div>

    <div>
        <label class="font-semibold block">Maximum Price (₱)</label>
        <input name="max_price" type="number" class="w-full border rounded p-2"
               value="{{ old('max_price', $business->max_price) }}" required>
    </div>

    <button class="bg-sky-600 text-white px-4 py-2 rounded">Next →</button>
</form>
@endsection
