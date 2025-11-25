<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantayan Island Tourism Portal – Admin</title>

    {{-- Tailwind (CDN ok for now) --}}
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

    <div class="flex">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-white shadow-lg h-screen fixed top-0 left-0 z-30">

            <div class="p-6 border-b">
                <h1 class="text-xl font-bold text-sky-700"><div class="flex items-center gap-3">
    {{-- LGU logo / initials --}}
    @if(auth()->user()->lgu_logo)
        <img src="{{ asset('storage/'.auth()->user()->lgu_logo) }}"
             alt="{{ auth()->user()->municipality }} logo"
             class="w-8 h-8 rounded-full object-cover">
    @else
        <div class="w-8 h-8 rounded-full bg-sky-600 text-white flex items-center justify-center text-xs">
            {{ strtoupper(substr(auth()->user()->municipality ?? 'LGU', 0, 2)) }}
        </div>
    @endif

    {{-- LGU name --}}
    <div class="flex flex-col">
        <span class="text-xs text-gray-500">Tourism Admin Panel</span>
        <span class="font-semibold">
            @if(auth()->user()->municipality)
                LGU {{ auth()->user()->municipality }}
            @else
                LGU Admin
            @endif
        </span>
    </div>
</div>
</h1>
                <p class="text-xs text-gray-500">Bantayan Island Tourism Portal</p>
            </div>

            <nav class="mt-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-6 py-3 hover:bg-sky-50 {{ request()->routeIs('admin.dashboard') ? 'bg-sky-100 font-semibold text-sky-700' : 'text-gray-700' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.businesses') }}"
                   class="block px-6 py-3 hover:bg-sky-50 {{ request()->routeIs('admin.businesses') ? 'bg-sky-100 font-semibold text-sky-700' : 'text-gray-700' }}">
                    Manage Businesses
                </a>

                <a href="{{ route('admin.attractions') }}"
   class="block px-6 py-3 hover:bg-sky-50 {{ request()->routeIs('admin.attractions*') ? 'bg-sky-100 font-semibold text-sky-700' : 'text-gray-700' }}">
    Manage Attractions
</a>


                <a href="{{ route('admin.bookings') }}"
                   class="block px-6 py-3 hover:bg-sky-50 {{ request()->routeIs('admin.bookings') ? 'bg-sky-100 font-semibold text-sky-700' : 'text-gray-700' }}">
                    All Bookings
                </a>

                <a href="{{ route('admin.users') }}"
                   class="block px-6 py-3 hover:bg-sky-50 {{ request()->routeIs('admin.users') ? 'bg-sky-100 font-semibold text-sky-700' : 'text-gray-700' }}">
                    All Users
                </a>

                <a href="{{ route('admin.analytics') }}"
                   class="block px-6 py-3 hover:bg-sky-50 {{ request()->routeIs('admin.analytics') ? 'bg-sky-100 font-semibold text-sky-700' : 'text-gray-700' }}">
                    Analytics Dashboard
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-6">
                    @csrf
                    <button class="w-full text-left px-6 py-3 text-red-600 hover:bg-red-50">
                        Logout
                    </button>
                </form>
            </nav>

        </aside>

        {{-- MAIN CONTENT AREA --}}
        <main class="ml-64 w-full min-h-screen p-6">
            @yield('content')
        </main>

    </div>
<script>
// GLOBAL modal handler using event delegation
document.addEventListener('click', function (event) {
    // OPEN modal
    const openBtn = event.target.closest('[data-modal-toggle]');
    if (openBtn) {
        const targetId = openBtn.getAttribute('data-modal-target');
        if (!targetId) return;

        const modal = document.getElementById(targetId);
        if (modal) {
            event.preventDefault();
            modal.classList.remove('hidden');
        }
        return; // don't also run close logic
    }

    // CLOSE modal
    const closeBtn = event.target.closest('[data-modal-hide]');
    if (closeBtn) {
        const targetId = closeBtn.getAttribute('data-modal-hide');
        if (!targetId) return;

        const modal = document.getElementById(targetId);
        if (modal) {
            event.preventDefault();
            modal.classList.add('hidden');
        }
    }
});
</script>



</body>
</html>
