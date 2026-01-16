<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('/logo.png') }}">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <x-logout-confirm />

    @include('dashboard.partials.header')

    <div class="flex">
        @include('dashboard.partials.sidebar')

        <main class="flex-1 min-h-screen pt-6 px-6 lg:px-12" style="margin-left:260px; margin-top:60px;">
            <div class="max-w-8xl mx-auto">
              @if(session('success') || session('status'))
        <div class="mb-4 flex items-center bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm rounded" role="alert">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('success') ?? session('status') }}</p>
        </div>
    @endif

              @if(session('error'))
        <div class="mb-4 flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm rounded" role="alert">
            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>

</html>