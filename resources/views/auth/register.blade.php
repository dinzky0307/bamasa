@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12 bg-white shadow-md rounded-lg p-6">

    <h1 class="text-2xl font-bold mb-6">Register</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-800 p-3 rounded">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded px-3 py-2 mt-1" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border rounded px-3 py-2 mt-1" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Password</label>
            <input type="password" name="password"
                   class="w-full border rounded px-3 py-2 mt-1" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full border rounded px-3 py-2 mt-1" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Account Type</label>
            <select name="role" class="w-full border rounded px-3 py-2 mt-1" required>
                <option value="">-- Select --</option>
                <option value="tourist" {{ old('role')=='tourist'?'selected':'' }}>Tourist</option>
                <option value="business" {{ old('role')=='business'?'selected':'' }}>Business Owner</option>
            </select>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
            Register
        </button>

        <p class="mt-4 text-sm text-center">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600">Login here</a>
        </p>
    </form>
</div>
@endsection
