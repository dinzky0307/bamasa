<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bantayan Island Tourism Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<meta name="theme-color" content="#0ea5e9">


</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    {{-- Top accent bar --}}
    <div class="bg-gradient-to-r from-sky-600 via-emerald-500 to-sky-500 text-xs text-white">
        <div class="max-w-6xl mx-auto px-4 py-1 flex items-center justify-between">
            <span class="hidden sm:inline">
                Official Tourism Reservation Portal for Bantayan Island, Cebu
            </span>
            <span>
                🌴 Discover · Book · Experience
            </span>
        </div>
    </div>

    {{-- Main navbar --}}
    <header class="bg-white/90 backdrop-blur shadow-sm sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            {{-- Logo + title --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-sky-500 to-emerald-500 flex items-center justify-center text-white text-lg font-bold shadow-sm">
                    BI
                </div>
                <div class="leading-tight">
                    <div class="font-semibold text-sm sm:text-base">
                        Bantayan Island
                    </div>
                    <div class="text-[11px] sm:text-xs text-slate-500">
                        Tourism & Reservation Portal
                    </div>
                </div>
            </a>

            {{-- Navigation --}}
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'text-sky-700 font-semibold' : 'text-slate-600 hover:text-sky-700' }}">
                    Home
                </a>

                <a href="{{ route('businesses.index') }}"
                   class="{{ request()->routeIs('businesses.*') ? 'text-sky-700 font-semibold' : 'text-slate-600 hover:text-sky-700' }}">
                    Places to Stay & Eat
                </a>

                <a href="{{ route('attractions.index') }}"
                   class="{{ request()->routeIs('attractions.*') ? 'text-sky-700 font-semibold' : 'text-slate-600 hover:text-sky-700' }}">
                    Attractions
                </a>

                @auth
                    @if(auth()->user()->role === 'business')
                        <a href="{{ route('owner.dashboard') }}"
                           class="{{ request()->routeIs('owner.*') ? 'text-sky-700 font-semibold' : 'text-slate-600 hover:text-sky-700' }}">
                            Business Portal
                        </a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ request()->routeIs('admin.*') ? 'text-sky-700 font-semibold' : 'text-slate-600 hover:text-sky-700' }}">
                            Admin Panel
                        </a>
                    @else
                        <a href="{{ route('bookings.mine') }}"
                           class="{{ request()->routeIs('bookings.mine') ? 'text-sky-700 font-semibold' : 'text-slate-600 hover:text-sky-700' }}">
                            My Bookings
                        </a>
                    @endif
                @endauth

               <a href="{{ route('announcements.index') }}"
   class="text-gray-700 hover:text-black">
    Island Info
</a>

            </nav>

            {{-- Auth buttons --}}
            <div class="flex items-center gap-2 text-sm">
                @guest
                    <a href="{{ route('login') }}"
                       class="hidden sm:inline-block px-3 py-1.5 rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100">
                        Log in
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-3 sm:px-4 py-1.5 rounded-full bg-sky-600 text-white hover:bg-sky-700">
                        Sign up
                    </a>
                @else
                    <span class="hidden sm:inline text-xs text-slate-500">
                        Hello, {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="px-3 py-1.5 rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    {{-- Page content --}}
    <main class="min-h-[70vh]">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-10 border-t bg-white">
        <div class="max-w-6xl mx-auto px-4 py-6 grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div>
                <div class="font-semibold mb-1">Bantayan Island Tourism</div>
                <p class="text-slate-500 text-xs">
                    A centralized portal to discover accommodations, attractions, and local experiences
                    across Bantayan, Santa Fe, and Madridejos.
                </p>
            </div>

            <div>
                <div class="font-semibold mb-1">Explore</div>
                <ul class="space-y-1 text-slate-500 text-xs">
                    <li><a href="{{ route('businesses.index') }}" class="hover:text-sky-700">Places to stay & eat</a></li>
                    <li><a href="{{ route('attractions.index') }}" class="hover:text-sky-700">Things to do</a></li>
                    @auth
                        <li><a href="{{ route('bookings.mine') }}" class="hover:text-sky-700">My bookings</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <div class="font-semibold mb-1">Powered by</div>
                <p class="text-slate-500 text-xs">
                    Local Government Units of Bantayan, Santa Fe & Madridejos, Cebu.
                    <br>
                    System developed as an academic research project.
                </p>
            </div>
        </div>
        <div class="border-t text-[11px] text-center text-slate-400 py-3 bg-slate-50">
            © {{ now()->year }} Bantayan Island Tourism Portal. All rights reserved.
        </div>
    </footer>

</body>
</html>
