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

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.status_label') }}</label>
                <select name="property_type_id" required
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                    <option value="">-- {{ __('messages.property.all_status') }} --</option>
                    @foreach($propertyTypes as $type)
                        <option value="{{ $type->id }}" {{ old('property_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
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
                    <option value="available">{{ __('messages.property.status_available') }}</option>
                    <option value="booked">{{ __('messages.property.status_booked') }}</option>
                    <option value="rented">{{ __('messages.property.status_rented') }}</option>
                    <option value="hidden">{{ __('messages.property.status_hidden') }}</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.description_label') }}</label>
                <textarea name="description" rows="5"
                          class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm"
                          placeholder="{{ __('messages.property.description_placeholder') }}">{{ old('description') }}</textarea>
            </div>

            <div class="flex flex-wrap gap-4 justify-end">
                <a href="{{ route('dashboard.properties.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold hover:bg-gray-300 transition">{{ __('messages.property.cancel_button') }}</a>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-full font-bold shadow-2xl hover:scale-[1.05] transform transition">
                    {{ __('messages.property.add_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const dropzone = document.getElementById('dropzone');
dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('border-indigo-400','bg-indigo-50'); });
dropzone.addEventListener('dragleave', e => { dropzone.classList.remove('border-indigo-400','bg-indigo-50'); });
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('border-indigo-400','bg-indigo-50');
    const dt = e.dataTransfer;
    document.getElementById('images').files = dt.files;
    previewImages();
});
</script>
@endsection