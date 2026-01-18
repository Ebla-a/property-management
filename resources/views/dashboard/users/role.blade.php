@extends('dashboard.layout')

@section('content')
@php $isRtl = app()->getLocale() == 'ar'; @endphp

<div class="lg:ms-64 container mx-auto p-6 max-w-3xl" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-6 text-center">
            {{ __('messages.user.change_role') }}
        </h1>

        {{-- User Info --}}
        <div class="mb-6 p-5 bg-gray-50 border border-gray-200 rounded-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                <p>
                    <span class="font-semibold text-gray-900">{{ __('messages.user.name') }}:</span>
                    {{ $user->name }}
                </p>
                <p>
                    <span class="font-semibold text-gray-900">{{ __('messages.user.email') }}:</span>
                    {{ $user->email }}
                </p>
            </div>
        </div>

        @php
            $currentRole = $user->getRoleNames()->first();
        @endphp

        <form method="POST"
              action="{{ route('dashboard.admin.users.change-role', $user->id) }}"
              class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- Role --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.user.role') }}</label>
                <select name="role" required
                        class="w-full border border-gray-300 rounded-xl p-3
                               focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                    <option value="admin" {{ $currentRole === 'admin' ? 'selected' : '' }}>
                        {{ __('messages.user.role_admin') }}
                    </option>
                    <option value="employee" {{ $currentRole === 'employee' ? 'selected' : '' }}>
                        {{ __('messages.user.role_employee') }}
                    </option>
                    <option value="customer" {{ $currentRole === 'customer' ? 'selected' : '' }}>
                        {{ __('messages.user.role_customer') }}
                    </option>
                </select>
                <p class="mt-2 text-sm text-gray-500">
                    {{ __('messages.user.role_change_hint') }}
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-wrap gap-4 {{ $isRtl ? 'justify-start' : 'justify-end' }}">
                <a href="{{ route('dashboard.admin.employees.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold
                          hover:bg-gray-300 transition">
                    {{ __('messages.user.cancel') }}
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-indigo-600
                               text-white rounded-full font-bold shadow-2xl
                               hover:scale-[1.03] transform transition
                               ">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 inline {{ $isRtl ? 'ml-2' : 'mr-2' }}"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('messages.user.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection