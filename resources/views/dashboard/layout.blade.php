<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <!-- inside <head> of your layout -->
<style>
  /* ensure x-cloak hides elements before Alpine boots */
  [x-cloak] { display: none !important; }
</style>

</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <x-logout-confirm />

    {{-- =====================
         Header
    ===================== --}}
    @include('dashboard.partials.header')

    <div class="flex">
        {{-- Sidebar --}}
        @include('dashboard.partials.sidebar')

        {{-- Main content area --}}
        <main class="flex-1 min-h-screen pt-6 px-6 lg:px-12" style="margin-left:260px; margin-top:60px;">
            <div class="max-w-8xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
