<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>RealEstateSys - Login</title>
    <link rel="icon" type="image/png" href="{{ asset('/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans">

<!-- Centered container -->
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-3xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden grid md:grid-cols-2">

            <!-- Left: Image Background -->
            <div class="relative hidden md:flex items-center justify-center px-10 py-12">
                <!-- Background image -->
                <img src="{{ asset('Estate.webp') }}"
                     alt="Real Estate"
                     class="absolute inset-0 w-full h-full object-cover">

                <!-- Overlay -->
                <div class="absolute inset-0 bg-indigo-900/50"></div>

                <!-- Content -->
                <div class="relative z-10 text-white">
                    <h2 class="text-3xl font-bold mb-2">Welcome Back</h2>
                    <p class="text-indigo-100 mb-8">
                        Sign in to continue managing your real estate portfolio with ease.
                    </p>

                </div>
            </div>

            <!-- Right: Login form -->
            <div class="px-8 py-10">
                <!-- Top logo for small screens -->
                <div class="flex items-center gap-3 md:hidden mb-6 justify-center">
                    <img src="{{ asset('logo.png') }}" alt="RealEstateSys Logo" class="h-10 w-10 rounded-lg object-cover">
                    <span class="text-indigo-600 text-2xl font-bold">RealEstateSys</span>
                </div>

                <div class="max-w-md mx-auto">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <h3 class="text-xl font-bold text-indigo-600 mb-6 text-center">
                        Login to your account
                    </h3>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="text-sm font-medium text-gray-700">Email Address</label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email') }}"
                                   required autofocus
                                   placeholder="you@example.com"
                                   class="mt-2 block w-full rounded-lg border border-gray-200 bg-white py-3 px-4 text-gray-800 shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                            <input id="password"
                                   name="password"
                                   type="password"
                                   required
                                   placeholder="********"
                                   class="mt-2 block w-full rounded-lg border border-gray-200 bg-white py-3 px-4 text-gray-800 shadow-sm
                                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between mt-2">
                            <label class="inline-flex items-center gap-2">
                                <input id="remember_me" name="remember" type="checkbox"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm text-gray-600">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 grid grid-cols-1 gap-3">
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-4 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                                Login
                            </button>


                        </div>
                    </form>

                    <p class="mt-6 text-center text-sm text-gray-400">
                        © 2026 RealEstateSys. All rights reserved.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
