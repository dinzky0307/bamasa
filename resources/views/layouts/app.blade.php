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
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between">
            <div class="flex items-center space-x-4">
                <a href="/" class="text-xl font-bold">Bantayan Portal</a>

                <a href="{{ route('businesses.index') }}" class="text-gray-700 hover:text-black">
                    Businesses
                </a>

                <a href="/attractions" class="text-gray-700 hover:text-black">
                    Attractions
                </a>
            </div>

            <div>
                @auth
                    <a href="/dashboard" class="text-gray-700 hover:text-black">Dashboard</a>
                @else
                    <a href="/login" class="text-gray-700 hover:text-black">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

</body>
</html>
