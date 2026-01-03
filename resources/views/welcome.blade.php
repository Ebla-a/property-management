<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

<!-- Navbar -->
<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo / Brand -->
            <a href="/" class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-600 text-white font-bold">
                    RS
                </span>
                <span class="text-2xl font-bold text-indigo-600">RealEstateSys</span>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">
                    Login
                </a>
                <a href="{{ route('register') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition">
                    Register
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button type="button" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>

<!-- Hero Section with Light Overlay -->
<section class="relative h-[550px] flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <img src="{{ asset('Estate.webp') }}" alt="Real Estate Background" class="absolute inset-0 w-full h-full object-cover z-0">

    <!-- Light Overlay -->
    <div class="absolute inset-0 bg-white bg-opacity-20 z-10"></div> <!-- تم تخفيف الشفافية -->

    <!-- Content -->
    <div class="relative z-20 text-center px-6 sm:px-8 lg:px-12">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white drop-shadow-lg mb-4">
            Manage Your Properties Easily
        </h1>
        <p class="text-lg sm:text-xl text-white drop-shadow mb-8">
            Track properties, bookings, and clients all in one place.
        </p>
        <div class="flex justify-center gap-4 flex-wrap">
            <a href="{{ route('login') }}"
               class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-1">
                Login Now
            </a>
            <a href="{{ route('register') }}"
               class="bg-white hover:bg-gray-100 text-indigo-600 font-bold px-6 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-1">
                Create Account
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="max-w-7xl mx-auto py-20 px-6 sm:px-8 lg:px-12">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-16">Quick Overview</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
        <div class="bg-white p-8 rounded-xl shadow-xl text-center hover:scale-105 transform transition">
            <p class="text-gray-400 uppercase mb-2">Total Properties</p>
            <p class="text-3xl font-extrabold text-indigo-600">120</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow-xl text-center hover:scale-105 transform transition">
            <p class="text-gray-400 uppercase mb-2">Bookings Today</p>
            <p class="text-3xl font-extrabold text-indigo-600">35</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow-xl text-center hover:scale-105 transform transition">
            <p class="text-gray-400 uppercase mb-2">Clients</p>
            <p class="text-3xl font-extrabold text-indigo-600">450</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow-xl text-center hover:scale-105 transform transition">
            <p class="text-gray-400 uppercase mb-2">Reports</p>
            <p class="text-3xl font-extrabold text-indigo-600">12</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-white mt-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-6 text-center text-gray-500">
        &copy; 2026 RealEstateSys. All rights reserved.
    </div>
</footer>

</body>
</html>
