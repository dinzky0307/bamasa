@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12 bg-white shadow-md rounded-lg p-6">

    <h1 class="text-2xl font-bold mb-6">Login</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-800 p-3 rounded">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border rounded px-3 py-2 mt-1" required autofocus>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Password</label>
            <input type="password" name="password"
                   class="w-full border rounded px-3 py-2 mt-1" required>
        </div>

        <div class="mb-4 flex items-center">
            <input id="remember" type="checkbox" name="remember" class="mr-2">
            <label for="remember">Remember Me</label>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
            Login
        </button>

        <p class="mt-4 text-sm text-center">
            Don't have an account?  
            <a href="{{ route('register') }}" class="text-blue-600">Register here</a>
        </p>
    </form>
</div>
@endsection
