<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    {{-- Header --}}
    @include('dashboard.partials.header')

    <div class="flex">
        {{-- Sidebar --}}
        @include('dashboard.partials.sidebar')

        {{-- Main content area --}}
        <main class="flex-1 min-h-screen pt-6 px-6 lg:px-12" style="margin-left:260px; margin-top:60px;">
            <div class="max-w-7xl mx-auto">
                {{-- Page title --}}
                <div class="mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-900">@yield('page_title', 'Dashboard')</h2>
                </div>

                {{-- Page content --}}
                <div class="space-y-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

</body>
</html>
