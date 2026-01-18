@extends('dashboard.layout')

@section('content')
@php $isRtl = app()->getLocale() == 'ar'; @endphp

<div class="lg:ms-64 container mx-auto p-6 max-w-3xl" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    {{-- Icon above the card --}}
    <div class="flex justify-center mt-8 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-20 w-20 text-indigo-600"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <circle cx="12" cy="7" r="3" stroke-width="1.5"/>
            <path d="M5 20c0-3.866 3.134-7 7-7s7 3.134 7 7"
                  stroke-width="1.5" stroke-linecap="round"/>
            <circle cx="18.5" cy="18.5" r="2.5"
                    stroke-width="1.5"/>
        </svg>
    </div>

    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-6 text-center">
            {{ __('messages.user.account_status') }}
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
                <p class="md:col-span-2">
                    <span class="font-semibold text-gray-900">{{ __('messages.user.current_status') }}:</span>
                    <span class="{{ $isRtl ? 'mr-2' : 'ml-2' }} px-3 py-1 rounded-full text-sm font-semibold
                        {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->is_active ? __('messages.user.active') : __('messages.user.inactive') }}
                    </span>
                </p>
            </div>
        </div>

        <form method="POST"
              action="{{ route('dashboard.admin.users.toggle-status', $user->id) }}"
              class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- Toggle --}}
            <div class="flex items-center gap-4">
                <input type="hidden" name="is_active" value="0">

                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ $user->is_active ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="relative w-11 h-6 bg-gray-300 rounded-full peer
                                peer-checked:bg-indigo-600 transition
                                after:content-[''] after:absolute after:top-[2px] 
                                {{ $isRtl ? 'after:right-[2px]' : 'after:left-[2px]' }} 
                                after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                peer-checked:after:translate-x-full {{ $isRtl ? 'peer-checked:after:-translate-x-5' : '' }}">
                    </div>
                    <span class="{{ $isRtl ? 'mr-3' : 'ml-3' }} text-gray-700 font-medium">
                        {{ __('messages.user.activate_account') }}
                    </span>
                </label>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-wrap gap-4 {{ $isRtl ? 'justify-start' : 'justify-end' }}">
                <a href="{{ route('dashboard.admin.employees.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold
                          hover:bg-gray-300 transition">
                    {{ __('messages.user.cancel') }}
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500
                               text-white rounded-full font-bold shadow-2xl
                               hover:scale-[1.03] transform transition
                               focus:outline-none focus:ring-4 focus:ring-indigo-200">
                    {{ __('messages.user.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection