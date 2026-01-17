@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-indigo-600">{{ __('messages.property.title') }}</h1>
        <a href="{{ route('dashboard.properties.create') }}"
           class="px-5 py-2 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full font-semibold shadow-lg hover:scale-[1.03] transform transition">
            {{ __('messages.property.add_property') }}
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-8 bg-white p-5 rounded-2xl shadow grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- City --}}
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.property.city_filter') }}</label>
            <input type="text"
                   name="city"
                   value="{{ $filters['city'] ?? '' }}"
                   placeholder="{{ __('messages.property.city_placeholder') }}"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        {{-- Min Price --}}
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.property.min_price_filter') }}</label>
            <input type="number"
                   name="min_price"
                   value="{{ $filters['min_price'] ?? '' }}"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        {{-- Max Price --}}
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">{{ __('messages.property.max_price_filter') }}</label>
            <input type="number"
                   name="max_price"
                   value="{{ $filters['max_price'] ?? '' }}"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        {{-- Actions --}}
        <div class="flex items-end gap-2">
            <button type="submit"
                    class="w-full px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-500 transition">
                {{ __('messages.property.filter_button') }}
            </button>
            <a href="{{ route('dashboard.properties.index') }}"
               class="px-4 py-2 bg-gray-200 rounded-full hover:bg-gray-300 transition">
                {{ __('messages.property.reset_button') }}
            </a>
        </div>

        {{-- Amenities --}}
        <div class="md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-700">{{ __('messages.property.amenities_filter') }}</label>
            <select name="amenity_ids[]" multiple class="w-full rounded-xl border-gray-300 h-40 p-2">
                @foreach($amenities as $amenity)
                    <option value="{{ $amenity->id }}" {{ in_array($amenity->id, (array)($filters['amenity_ids'] ?? [])) ? 'selected' : '' }}>
                        {{ $amenity->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Property Types --}}
        <div class="md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-700">{{ __('messages.sidebar.properties') }}</label>
            <select name="property_types[]" multiple class="w-full rounded-xl border-gray-300 h-40 p-2">
                @foreach($propertyTypes as $type)
                    <option value="{{ $type->id }}" {{ in_array($type->id, (array)($filters['property_types'] ?? [])) ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Properties Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($properties as $property)
            <div class="border rounded-2xl shadow-lg overflow-hidden bg-white hover:shadow-2xl transition">
                <div class="h-52 bg-gray-100">
                    @if($property->images->first())
                        <img src="{{ asset('storage/'.$property->images->first()->path) }}" class="w-full h-52 object-cover">
                    @else
                        <div class="flex items-center justify-center h-52 text-gray-400">
                            {{ __('messages.property.no_images') }}
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-lg font-semibold">{{ $property->title }}</h2>
                        @if($property->propertyType)
                            <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full">
                                {{ $property->propertyType->name }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500">{{ $property->city }}</p>
                    <p class="text-green-700 font-medium my-2">${{ number_format($property->price, 2) }}</p>

                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('dashboard.properties.show', $property->id) }}"
                           class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">{{ __('messages.property.view_button') }}</a>

                        <a href="{{ route('dashboard.properties.edit', $property->id) }}"
                           class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-sm">{{ __('messages.property.edit_button') }}</a>

                        <form action="{{ route('dashboard.properties.destroy', $property->id) }}" method="POST"
                              onsubmit="event.preventDefault(); window.currentDeleteForm = this; window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-property' }));">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-sm">
                                {{ __('messages.property.delete_button') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 p-6">{{ __('messages.property.no_properties') }}</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $properties->appends(request()->query())->links('pagination::tailwind') }}</div>
</div>

<x-confirm-modal id="delete-property" title="{{ __('messages.property.delete_confirm_title') }}" message="{{ __('messages.property.delete_confirm_message') }}">
    <button type="button" @click="if (window.currentDeleteForm) window.currentDeleteForm.submit(); open = false"
            class="px-5 py-2 rounded-full bg-red-600 text-white hover:bg-red-700 transition">
        {{ __('messages.property.delete_confirm_button') }}
    </button>
</x-confirm-modal>
@endsection