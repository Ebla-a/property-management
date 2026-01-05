@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    <div class="bg-white shadow-xl rounded-2xl p-8 space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $property->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $property->city ?? '---' }}
                    @if($property->neighborhood) • {{ $property->neighborhood }} @endif
                </p>
            </div>

<div class="flex flex-wrap items-center gap-3">
    <a href="{{ route('dashboard.properties.edit', $property->id) }}"
       class="inline-flex items-center gap-2 px-6 py-2.5
              bg-yellow-100 text-yellow-800 border border-yellow-200
              rounded-full font-semibold shadow-sm
              hover:bg-yellow-200 transition">
        <span>Edit</span>
    </a>

    <a href="{{ route('dashboard.properties.index') }}"
       class="inline-flex items-center gap-2 px-6 py-2.5
              bg-gray-100 text-gray-700 border border-gray-200
              rounded-full font-semibold shadow-sm
              hover:bg-gray-200 transition">
        <span>Back</span>
    </a>
</div>

        </div>

        {{-- Images --}}
        @php
            $mainImage = $property->images->firstWhere('is_main', true) ?? $property->images->first();
        @endphp

        <div class="space-y-4">
            {{-- Main Image --}}
            <div class="rounded-2xl overflow-hidden shadow-md bg-gray-100">
                @if($mainImage)
                    <img src="{{ asset('storage/'.$mainImage->path) }}"
                         onclick="openLightbox('{{ asset('storage/'.$mainImage->path) }}')"
                         class="w-full h-[420px] object-cover cursor-pointer"
                         alt="{{ $mainImage->alt ?? $property->title }}">
                @else
                    <div class="h-[420px] flex items-center justify-center text-gray-400">
                        No image available
                    </div>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($property->images->count() > 1)
                <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                    @foreach($property->images as $img)
                        <img src="{{ asset('storage/'.$img->path) }}"
                             onclick="openLightbox('{{ asset('storage/'.$img->path) }}')"
                             class="h-24 w-full object-cover rounded-xl cursor-pointer border hover:ring-2 hover:ring-indigo-400 transition"
                             alt="{{ $img->alt }}">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Details --}}
            <div class="md:col-span-2 space-y-6">

                {{-- Description --}}
                <div class="bg-gray-50 p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $property->description ?? 'No description provided.' }}
                    </p>
                </div>

                {{-- Amenities --}}
                <div class="bg-gray-50 p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-semibold mb-3">Amenities</h3>
                    @if($property->amenities->isNotEmpty())
                        <div class="flex flex-wrap gap-3">
                            @foreach($property->amenities as $a)
                                <span class="px-4 py-2 bg-white border rounded-full text-sm shadow-sm">
                                    {{ $a->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No amenities listed.</p>
                    @endif
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">

                {{-- Price & Status --}}
                <div class="bg-white p-6 rounded-2xl shadow">
                    <p class="text-sm text-gray-500">Price</p>
                    <p class="text-3xl font-extrabold text-green-600 mb-4">
                        ${{ number_format($property->price, 2) }}
                    </p>

                    @php
                        $statusColors = [
                            'available' => 'bg-green-100 text-green-800',
                            'booked' => 'bg-yellow-100 text-yellow-800',
                            'rented' => 'bg-red-100 text-red-800',
                            'hidden' => 'bg-gray-100 text-gray-700',
                        ];
                    @endphp

                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $statusColors[$property->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($property->status) }}
                    </span>
                </div>

                {{-- Specs --}}
                <div class="bg-white p-6 rounded-2xl shadow space-y-3 text-sm">
                    <p><strong>Rooms:</strong> {{ $property->rooms ?? '---' }}</p>
                    <p><strong>Area:</strong> {{ $property->area ? $property->area.' m²' : '---' }}</p>
                    <p><strong>Furnished:</strong> {{ $property->is_furnished ? 'Yes' : 'No' }}</p>
                    <p><strong>Address:</strong> {{ $property->address ?? '---' }}</p>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox"
     onclick="closeLightbox()"
     class="fixed inset-0 z-50 hidden bg-black/70 flex items-center justify-center p-6">

    <div onclick="event.stopPropagation()"
         class="relative bg-white rounded-2xl p-4 max-w-4xl w-full shadow-xl">

        {{-- Close button --}}
        <button onclick="closeLightbox()"
                class="absolute top-3 right-3 bg-black/60 text-white rounded-full p-2 hover:bg-black transition">
            ✕
        </button>

        <img id="lightbox-img"
             src=""
             class="w-full max-h-[80vh] object-contain rounded-xl">
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
