@extends('layouts.owner')

@section('content')
<h1 class="text-2xl font-bold mb-4">Step 2: Contact & Location</h1>

<form action="{{ route('owner.wizard.step2') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
    @csrf

    <div>
        <label class="font-semibold block">Address</label>
        <input name="address" type="text" class="w-full border rounded p-2"
               value="{{ old('address', $business->address) }}" required>
    </div>

    <div>
        <label class="font-semibold block">Phone</label>
        <input name="phone" type="text" class="w-full border rounded p-2"
               value="{{ old('phone', $business->phone) }}" required>
    </div>

    <div>
        <label class="font-semibold block">Email</label>
        <input name="email" type="email" class="w-full border rounded p-2"
               value="{{ old('email', $business->email) }}" required>
    </div>

    <div>
        <label class="font-semibold block">Website</label>
        <input name="website" type="url" class="w-full border rounded p-2"
               value="{{ old('website', $business->website) }}">
    </div>

    <div>
        <label class="font-semibold block">Facebook Page</label>
        <input name="facebook_page" type="url" class="w-full border rounded p-2"
               value="{{ old('facebook_page', $business->facebook_page) }}">
    </div>

    <button class="bg-sky-600 text-white px-4 py-2 rounded">Next →</button>
</form>
@endsection
