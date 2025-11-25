@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-10">
    <div class="max-w-md w-full">

        {{-- Heading --}}
        <div class="mb-6 text-center">
            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">
                Welcome back
            </p>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">
                Login to your account
            </h1>
            <p class="text-xs text-slate-500 mt-2">
                Access your bookings, manage your business, or administer the tourism portal.
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">

            {{-- Flash / error messages --}}
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs px-3 py-2 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs px-3 py-2 rounded-lg">
                    <p class="font-semibold mb-1">Please check the following:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4 text-sm">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Email address
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                        placeholder="you@example.com"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                        placeholder="••••••••"
                    >
                </div>

                {{-- Role hint (optional text, not field) --}}
                <p class="text-[11px] text-slate-500">
                    Use your registered email as a tourist, business owner, or LGU administrator.
                </p>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium py-2.5 rounded-lg shadow-sm">
                    Login
                </button>
            </form>
        </div>

        {{-- Footer link --}}
        <div class="mt-4 text-center text-xs text-slate-500">
            Don’t have an account yet?
            <a href="{{ route('register') }}" class="text-sky-700 hover:text-sky-900 font-semibold">
                Create one
            </a>
        </div>

        {{-- Hint for testing --}}
        <div class="mt-4 text-[11px] text-slate-400 text-center">
            Tip: Use your LGU admin, business owner, or tourist test accounts for evaluation.
        </div>
    </div>
</div>
@endsection
