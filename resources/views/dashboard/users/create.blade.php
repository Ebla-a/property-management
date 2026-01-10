{{-- resources/views/dashboard/users/create.blade.php --}}
@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    {{-- Icon above the card (larger, purple, no container, space from top) --}}
    <div class="flex justify-center mt-8 mb-6">
        <!-- Bigger purple user+plus icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-[90px] w-[90px] text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <!-- head -->
            <circle cx="10.5" cy="8" r="2.5" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <!-- shoulders/body -->
            <path d="M4 20c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <!-- plus sign -->
            <path d="M19 11v4M17 13h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
    </div>

    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-6 text-center">Create New Employee</h1>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.admin.employees.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Full Name --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="John Doe">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="email@example.com">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Password --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="••••••••">
                    <p class="mt-2 text-sm text-gray-500">Use at least 8 characters.</p>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="••••••••">
                </div>
            </div>

            {{-- Role --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700">Role</label>
                <select name="role"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                    <option value="employee" {{ old('role', 'employee') == 'employee' ? 'selected' : '' }}>Employee</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
            </div>


            {{-- Buttons --}}
            <div class="flex flex-wrap gap-4 justify-end">
                <a href="{{ route('dashboard.admin.employees.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold hover:bg-gray-300 transition">Cancel</a>

                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full font-bold shadow-2xl hover:scale-[1.03] transform transition focus:outline-none focus:ring-4 focus:ring-indigo-200">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
