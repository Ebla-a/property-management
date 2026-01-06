<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>RealEstateSys - Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans">

<!-- Centered container -->
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8">


            <h2 class="text-xl font-bold text-indigo-600 text-center mb-2">
                Forgot your password?
            </h2>
            <p class="text-sm text-gray-500 text-center mb-6">
                Enter your email address and we’ll send you a password reset link.
            </p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="you@example.com"
                           class="mt-2 block w-full rounded-lg border border-gray-200 bg-white py-3 px-4 text-gray-800 shadow-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action -->
                <button type="submit"
                        class="w-full mt-4 inline-flex justify-center items-center bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-4 py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                    Email Password Reset Link
                </button>
            </form>

            <!-- Back to login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                   class="text-sm text-indigo-600 hover:underline">
                    Back to login
                </a>
            </div>

            <p class="mt-6 text-center text-xs text-gray-400">
                © 2026 RealEstateSys. All rights reserved.
            </p>

        </div>
    </div>
</div>

</body>
</html>
