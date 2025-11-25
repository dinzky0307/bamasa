@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-10">
    <div class="max-w-md w-full">

        {{-- Heading --}}
        <div class="mb-6 text-center">
            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">
                Join the Bantayan Island portal
            </p>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">
                Create your account
            </h1>
            <p class="text-xs text-slate-500 mt-2">
                Tourists can save bookings, while business owners and LGUs log in with accounts provided to them.
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">

            {{-- Flash / error messages --}}
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

            <form method="POST" action="{{ route('register') }}" class="space-y-4 text-sm">
                @csrf

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">
                        Full name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                        placeholder="Juan Dela Cruz"
                    >
                </div>

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
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                        placeholder="you@example.com"
                    >
                </div>

                {{-- Country --}}
<div>
    <label class="block text-xs font-semibold text-slate-600 mb-1">
        Country
    </label>
    <input
        type="text"
        name="country"
        value="{{ old('country', 'Philippines') }}"
        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
        placeholder="Philippines"
    >
</div>


                {{-- Password --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
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
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                            Confirm password
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                {{-- Role info text (not a field) --}}
                <div class="bg-slate-50 border border-slate-100 rounded-lg px-3 py-2 text-[11px] text-slate-500">
                    By default, new accounts are created as <span class="font-semibold">tourists</span>.
                    <br>
                    Business owner and LGU admin accounts are usually registered by the system administrator.
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium py-2.5 rounded-lg shadow-sm">
                    Sign up
                </button>
            </form>
        </div>

        {{-- Footer link --}}
        <div class="mt-4 text-center text-xs text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-sky-700 hover:text-sky-900 font-semibold">
                Login instead
            </a>
        </div>
    </div>
</div>
@endsection
