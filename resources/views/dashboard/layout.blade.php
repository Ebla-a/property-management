<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('/logo.png') }}">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
      [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <x-logout-confirm />

    @include('dashboard.partials.header')

    <div class="flex">
        @include('dashboard.partials.sidebar')

        <main class="flex-1 min-h-screen pt-6 px-6 lg:px-12" style="margin-left:260px; margin-top:60px;">
            <div class="max-w-8xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- ✅ Alpine.js يجب تحميله قبل أي مكون يعتمد عليه -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
