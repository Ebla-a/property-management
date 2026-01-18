@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-6 text-center">{{ __('messages.property.add_property') }}</h1>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.title_label') }}</label>
                <input name="title" value="{{ old('title') }}"
                       class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                       placeholder="{{ __('messages.property.title_placeholder') }}" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.city_label') }}</label>
                    <input name="city" value="{{ old('city') }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.property.city_placeholder') }}">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.neighborhood_label') }}</label>
                    <input name="neighborhood" value="{{ old('neighborhood') }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.property.neighborhood_placeholder') }}">
                </div>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.address_label') }}</label>
                <input name="address" value="{{ old('address') }}"
                       class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                       placeholder="{{ __('messages.property.address_placeholder') }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.rooms_label') }}</label>
                    <input name="rooms" type="number" value="{{ old('rooms') }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.property.rooms_placeholder') }}">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.area_label') }}</label>
                    <input name="area" type="text" value="{{ old('area') }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.property.area_placeholder') }}">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.price_label') }}</label>
                    <input name="price" type="text" value="{{ old('price') }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                           placeholder="{{ __('messages.property.price_placeholder') }}">
                </div>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.status_label') }}</label>
                <select name="status"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>{{ __('messages.property.status_list.available') }}</option>
                    <option value="booked" {{ old('status') == 'booked' ? 'selected' : '' }}>{{ __('messages.property.status_list.booked') }}</option>
                    <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>{{ __('messages.property.status_list.rented') }}</option>
                    <option value="hidden" {{ old('status') == 'hidden' ? 'selected' : '' }}>{{ __('messages.property.status_list.hidden') }}</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.description_label') }}</label>
                <textarea name="description" rows="5"
                          class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                          placeholder="{{ __('messages.property.description_placeholder') }}">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.images_label') }}</label>

                <div id="dropzone"
                     class="w-full border-2 border-dashed border-gray-300 rounded-2xl p-8 flex flex-col items-center justify-center cursor-pointer hover:border-indigo-400 transition relative"
                     onclick="document.getElementById('images').click()">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 16V4h10v12m-5-4v8m-4 0h8" />
                    </svg>
                    <p class="text-gray-500 text-center">{{ __('messages.property.images_placeholder') }}</p>

                    <div id="preview" class="mt-4 flex flex-wrap gap-4 w-full justify-center"></div>
                </div>

                <input id="images" type="file" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages()">
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.amenities_label') }}</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($amenities as $a)
                        <label class="inline-flex items-center bg-gray-100 rounded-xl px-3 py-2 hover:bg-indigo-50 cursor-pointer">
                            <input type="checkbox" name="amenity_ids[]" value="{{ $a->id }}"
                                   class="form-checkbox h-5 w-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-400">
                            <span class="ml-2 text-gray-700">{{ $a->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap gap-4 justify-end">
                <a href="{{ route('dashboard.properties.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold hover:bg-gray-300 transition">{{ __('messages.property.cancel_button') }}</a>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full font-bold shadow-2xl hover:scale-[1.05] transform transition focus:outline-none focus:ring-4 focus:ring-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('messages.property.add_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Logic remains identical as requested
function previewImages() {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    const files = document.getElementById('images').files;

    if(files.length === 0) return;

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-28 h-28 object-cover rounded-xl shadow-md';
            preview.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
}

const dropzone = document.getElementById('dropzone');
dropzone.addEventListener('dragover', e => {
    e.preventDefault();
    dropzone.classList.add('border-indigo-400', 'bg-indigo-50');
});
dropzone.addEventListener('dragleave', e => {
    dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
});
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('border-indigo-400', 'bg-indigo-50');
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('images').files = files;
    previewImages();
});
</script>
@endsection