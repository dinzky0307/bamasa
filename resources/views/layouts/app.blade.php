<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bantayan Island Tourism Portal</title>
   
     <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-white shadow mb-6">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        {{-- Left side: logo + links --}}
        <div class="flex items-center space-x-4">
            <a href="{{ route('home') }}" class="text-xl font-bold">Bantayan Portal</a>

            <a href="{{ route('businesses.index') }}" class="text-gray-700 hover:text-black">
                Businesses
            </a>

            <a href="{{ route('attractions.index') }}" class="hover:text-gray-900">
    Attractions
</a>
        </div>

        {{-- Right side: auth / owner links --}}
        <div class="flex items-center space-x-4">
            @auth
                {{-- Show Owner Dashboard link if business owner --}}
                @if(auth()->user()->role === 'business')
                    <a href="{{ route('owner.dashboard') }}" class="text-gray-700 hover:text-black">
                        Owner Dashboard
                    </a>
                @endif

                {{-- My bookings link for tourists --}}
                @if(auth()->user()->role === 'tourist')
                    <a href="{{ route('bookings.mine') }}" class="text-gray-700 hover:text-black">
                        My Bookings
                    </a>
                @endif

                {{-- Logout button --}}
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-black">
                        Logout ({{ auth()->user()->name }})
                    </button>
                </form>

                @auth
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="hover:text-black">Admin Dashboard</a>
    <a href="{{ route('admin.businesses') }}" class="hover:text-black">Manage Businesses</a>
    <a href="{{ route('admin.analytics') }}" class="hover:text-black font-bold">Analytics</a>
    @endif

    {{-- existing owner / tourist links and logout here --}}
@endauth

            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-black">
                    Login
                </a>
                <a href="{{ route('register') }}" class="text-gray-700 hover:text-black">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>

    @yield('content')

    <script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
    
    
    <script>
document.addEventListener('DOMContentLoaded', () => {
    // Open modal
    document.querySelectorAll('[data-modal-toggle]').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-modal-target');
            if (!targetId) return;

            const modal = document.getElementById(targetId);
            if (modal) {
                modal.classList.remove('hidden');
            }
        });
    });

    // Close modal
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-modal-hide');
            if (!targetId) return;

            const modal = document.getElementById(targetId);
            if (modal) {
                modal.classList.add('hidden');
            }
        });
    });
});
</script>
</body>
</html>
