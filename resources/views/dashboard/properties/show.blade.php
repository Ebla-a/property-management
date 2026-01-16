@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    <div class="bg-white shadow-xl rounded-2xl p-8 space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $property->title }}</h1>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('dashboard.properties.edit', $property->id) }}"
                   class="px-6 py-2.5 bg-yellow-100 text-yellow-800 border rounded-full font-semibold">
                    {{ __('messages.property.edit_button') }}
                </a>
                <a href="{{ route('dashboard.properties.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 border rounded-full font-semibold">
                    {{ __('messages.property.back_button') }}
                </a>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                {{-- Description --}}
                <div class="bg-gray-50 p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.property.description_heading') }}</h3>
                    <p class="text-gray-700">{{ $property->description ?? __('messages.property.no_description') }}</p>
                </div>

                {{-- Amenities --}}
                <div class="bg-gray-50 p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-semibold mb-3">{{ __('messages.property.amenities_heading') }}</h3>
                    @forelse($property->amenities as $a)
                        <span class="px-4 py-2 bg-white border rounded-full text-sm mx-1">{{ $a->name }}</span>
                    @empty
                        <p class="text-gray-500">{{ __('messages.property.no_amenities') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4">
                {{-- Price --}}
                <div class="bg-white p-6 rounded-2xl shadow">
                    <p class="text-sm text-gray-500">{{ __('messages.property.price_heading') }}</p>
                    <p class="text-3xl font-extrabold text-green-600">
                        ${{ number_format($property->price, 2) }}
                    </p>
                    <span class="mt-2 inline-block px-4 py-1 rounded-full text-sm bg-indigo-100">
                        {{ __('messages.property.status_' . $property->status) }}
                    </span>
                </div>

                {{-- Specs --}}
                <div class="bg-white p-6 rounded-2xl shadow space-y-3">
                    <h3 class="font-bold border-b pb-2">{{ __('messages.property.specs_heading') }}</h3>
                    <p><strong>{{ __('messages.property.rooms_spec') }}:</strong> {{ $property->rooms }}</p>
                    <p><strong>{{ __('messages.property.area_spec') }}:</strong> {{ $property->area }} m²</p>
                    <p><strong>{{ __('messages.property.address_spec') }}:</strong> {{ $property->address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.getElementById('lightbox-img').src = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endsection