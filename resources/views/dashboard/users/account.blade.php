{{-- resources/views/dashboard/users/account.blade.php --}}
@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">

    {{-- Icon above the card --}}
    <div class="flex justify-center mt-8 mb-6">
        <!-- account status icon -->
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-20 w-20 text-indigo-600"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- user -->
            <circle cx="12" cy="7" r="3" stroke-width="1.5"/>
            <path d="M5 20c0-3.866 3.134-7 7-7s7 3.134 7 7"
                  stroke-width="1.5" stroke-linecap="round"/>
            <!-- status circle -->
            <circle cx="18.5" cy="18.5" r="2.5"
                    stroke-width="1.5"/>
        </svg>
    </div>

    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-6 text-center">
            User Account Status
        </h1>

        {{-- User Info --}}
        <div class="mb-6 p-5 bg-gray-50 border border-gray-200 rounded-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                <p>
                    <span class="font-semibold text-gray-900">Name:</span>
                    {{ $user->name }}
                </p>
                <p>
                    <span class="font-semibold text-gray-900">Email:</span>
                    {{ $user->email }}
                </p>
                <p class="md:col-span-2">
                    <span class="font-semibold text-gray-900">Current Status:</span>
                    <span class="ml-2 px-3 py-1 rounded-full text-sm font-semibold
                        {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
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
                                peer-checked:bg-indigo-600 transition">
                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full
                                    transition peer-checked:translate-x-5"></div>
                    </div>
                    <span class="ml-3 text-gray-700 font-medium">
                        Activate this account
                    </span>
                </label>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-wrap gap-4 justify-end">
                <a href="{{ route('dashboard.admin.employees.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold
                          hover:bg-gray-300 transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500
                               text-white rounded-full font-bold shadow-2xl
                               hover:scale-[1.03] transform transition
                               focus:outline-none focus:ring-4 focus:ring-indigo-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
