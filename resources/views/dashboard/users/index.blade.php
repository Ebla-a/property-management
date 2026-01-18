@extends('dashboard.layout')

@section('content')
@php $isRtl = app()->getLocale() == 'ar'; @endphp

<div class="lg:ms-50 p-6" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="rounded-full bg-indigo-600 p-3 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14v9a2 2 0 01-2 2H7a2 2 0 01-2-2v-9z" />
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-semibold text-indigo-600">{{ __('messages.user.management') }}</h1>
                <p class="text-sm text-gray-500">{{ __('messages.user.management_description') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.admin.employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('messages.user.add_employee') }}
            </a>
        </div>
    </div>
    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-100">
            {{ session('error') }}
        </div>
    @endif

    {{-- Card container --}}
    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-100 overflow-hidden">
        {{-- Table wrapper for horizontal scroll --}}
        <div class="overflow-x-auto overflow-y-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-white">
                    <tr>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium text-gray-600">{{ __('messages.user.name') }}</th>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium text-gray-600">{{ __('messages.user.email') }}</th>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium text-gray-600">{{ __('messages.user.role') }}</th>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium text-gray-600">{{ __('messages.user.active') }}</th>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium text-gray-600">{{ __('messages.user.actions') }}</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-indigo-50/40 transition-colors">
                            <td class="px-4 py-3 flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 font-medium">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-800">{{ $user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ __('messages.user.joined') }} {{ $user->created_at->format('M j, Y') }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>

                            <td class="px-4 py-3">
                                @php $role = $user->getRoleNames()->first(); @endphp
                                @if($role)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ __('messages.user.role_' . $role) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">{{ __('messages.user.active') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">{{ __('messages.user.inactive') }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 space-x-2 whitespace-nowrap">
                                <a href="{{ route('dashboard.admin.users.edit-role', $user->id) }}" class="inline-flex items-center gap-2 px-3 py-1 bg-white ring-1 ring-yellow-100 text-yellow-800 rounded hover:bg-yellow-50">
                                    {{ __('messages.user.change_role') }}
                                </a>

                                <a href="{{ route('dashboard.admin.users.edit-status', $user->id) }}" class="inline-flex items-center gap-2 px-3 py-1 bg-white ring-1 ring-blue-100 text-blue-800 rounded hover:bg-blue-50">
                                    {{ __('messages.user.account_status') }}
                                </a>

                                @if(auth()->id() === $user->id)
                                    <button disabled class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed" title="{{ __('messages.user.cannot_delete_own_account') }}">{{ __('messages.user.delete') }}</button>
                                @else
                                    <button
                                        type="button"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-delete-{{ $user->id }}' }))"
                                    >
                                        {{ __('messages.user.delete') }}
                                    </button>

                                    <x-confirm-modal
                                        id="confirm-delete-{{ $user->id }}"
                                        title="{{ __('messages.user.delete_user') }}"
                                        message="{{ __('messages.user.delete_confirmation') }}"
                                        cancelText="{{ __('messages.user.cancel') }}"
                                    >
                                        <form action="{{ route('dashboard.admin.users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                                {{ __('messages.user.delete') }}
                                            </button>
                                        </form>
                                    </x-confirm-modal>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Empty state --}}
        @if($users->isEmpty())
            <div class="p-6 text-center text-gray-500">{{ __('messages.user.no_users_found') }}</div>
        @endif

        {{-- Pagination footer --}}
        <div class="px-4 py-4 border-t border-gray-100 bg-white flex items-center justify-between">
            <div class="text-sm text-gray-500">
                {{ __('messages.user.showing') }} {{ $users->firstItem() ?? 0 }} {{ __('messages.user.to') }} {{ $users->lastItem() ?? 0 }} {{ __('messages.user.of') }} {{ $users->total() }} {{ __('messages.user.users') }}
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection