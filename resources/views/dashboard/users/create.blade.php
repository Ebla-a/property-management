@extends('dashboard.layout')

@section('content')
@php $isRtl = app()->getLocale() == 'ar'; @endphp

<div class="lg:ms-64 container mx-auto p-6 max-w-4xl" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    {{-- Icon above the card --}}
    <div class="flex justify-center mt-8 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-[90px] w-[90px] text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <circle cx="10.5" cy="8" r="2.5" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <path d="M4 20c0-3.314 2.686-6 6-6s6 2.686 6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <path d="M19 11v4M17 13h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </svg>
    </div>

    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-6 text-center">
            {{ __('messages.user.create_employee') }}
        </h1>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc {{ $isRtl ? 'mr-5' : 'ml-5' }}">
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
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.user.full_name') }}</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.user.name_placeholder') }}">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.user.email_address') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.user.email_placeholder') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Password --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.user.password') }}</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.user.password_placeholder') }}">
                    <p class="mt-2 text-sm text-gray-500">{{ __('messages.user.password_hint') }}</p>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.user.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.user.password_placeholder') }}">
                </div>
            </div>

            {{-- Role --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.user.role') }}</label>
                <select name="role"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                    <option value="employee" {{ old('role', 'employee') == 'employee' ? 'selected' : '' }}>{{ __('messages.user.role_employee') }}</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('messages.user.role_admin') }}</option>
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>{{ __('messages.user.role_customer') }}</option>
                </select>
            </div>


            {{-- Buttons --}}
            <div class="flex flex-wrap gap-4 {{ $isRtl ? 'justify-start' : 'justify-end' }}">
                <a href="{{ route('dashboard.admin.employees.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold hover:bg-gray-300 transition">
                   {{ __('messages.user.cancel') }}
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full font-bold shadow-2xl hover:scale-[1.03] transform transition focus:outline-none focus:ring-4 focus:ring-indigo-200">
                    {{ __('messages.user.create') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection