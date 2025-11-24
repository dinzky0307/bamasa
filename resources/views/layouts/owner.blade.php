<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Owner Portal</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">

<div class="flex">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white shadow h-screen sticky top-0">
        <div class="p-4 border-b">
            <h2 class="font-bold text-lg text-sky-700">Owner Portal</h2>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('owner.dashboard') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100 
               {{ request()->routeIs('owner.dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('owner.bookings.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100
               {{ request()->routeIs('owner.bookings.*') ? 'bg-gray-200 font-semibold' : '' }}">
                Manage Bookings
            </a>

            <a href="{{ route('owner.business.edit') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100
               {{ request()->routeIs('owner.business.*') ? 'bg-gray-200 font-semibold' : '' }}">
                Manage Business
            </a>

            <a href="{{ route('owner.analytics') }}"
               class="block px-3 py-2 rounded hover:bg-gray-100
               {{ request()->routeIs('owner.analytics') ? 'bg-gray-200 font-semibold' : '' }}">
                Analytics Dashboard
            </a>

            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                @csrf
                <button class="w-full text-left px-3 py-2 rounded bg-red-100 text-red-700 hover:bg-red-200">
                    Log Out
                </button>
            </form>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-8">
        @yield('content')
    </main>

</div>

</body>
</html>
