@extends('dashboard.layout')

@section('title', __('messages.sidebar.amenities'))
@section('page_title', __('messages.sidebar.amenities'))

@section('content')
<div class="flex justify-between items-center mb-6 {{ app()->getLocale() == 'ar' ? 'flex-row-reverse' : '' }}">
    <h3 class="text-lg font-semibold">{{ __('messages.amenity.amenities_list') }}</h3>

    <a href="{{ route('dashboard.amenities.create') }}"
        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
        + {{ __('messages.amenity.add_amenity') }}
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <table class="min-w-full text-sm {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 font-semibold text-gray-700 w-16">#</th>
                <th class="px-6 py-3 font-semibold text-gray-700">{{ __('messages.amenity.amenity_name') }}</th>
                <th class="px-6 py-3 font-semibold text-gray-700 {{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }} w-40">
                    {{ __('messages.user.actions') }}
                </th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($amenities as $amenity)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3 text-gray-700">
                    {{ $amenity->id }}
                </td>

                <td class="px-6 py-3 font-medium text-gray-900">
                    {{ $amenity->name }}
                </td>

                <td class="px-6 py-3 {{ app()->getLocale() == 'ar' ? 'text-left' : 'text-right' }}">
                    <div class="inline-flex gap-2">
                        <a href="{{ route('dashboard.amenities.edit', $amenity) }}"
                            class="px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            {{ __('messages.amenity.edit_button') }}
                        </a>

                        <form method="POST" action="{{ route('dashboard.amenities.destroy', $amenity) }}" x-data
                            @submit.prevent="window.currentAmenityDeleteForm = $el; window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-amenity' }));">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                {{ __('messages.amenity.delete_button') }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                    {{ __('messages.reports.no_data') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<x-confirm-modal
    id="delete-amenity"
    title="{{ __('messages.amenity.delete_confirm_title') }}"
    message="{{ __('messages.amenity.confirm_delete_msg') }}"
    confirmText="{{ __('messages.amenity.delete') }}"
    cancelText="{{ __('messages.booking.cancel') }}"
>
    <button type="button" class="px-5 py-2 rounded-full bg-red-600 text-white hover:bg-red-700 transition"
        @click="if (window.currentAmenityDeleteForm) { window.currentAmenityDeleteForm.submit(); }">
        {{ __('messages.amenity.delete') }}
    </button>
</x-confirm-modal>
@endsection