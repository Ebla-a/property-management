@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-6 text-center">{{ __('messages.property.edit_property') }}</h1>

        <form action="{{ route('dashboard.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.title_label') }}</label>
                <input name="title" value="{{ old('title', $property->title) }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 shadow-sm" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.status_label') }}</label>
                    <select name="status" class="w-full border border-gray-300 rounded-xl p-3 shadow-sm">
                        <option value="available" {{ $property->status == 'available' ? 'selected' : '' }}>{{ __('messages.property.status_available') }}</option>
                        <option value="booked" {{ $property->status == 'booked' ? 'selected' : '' }}>{{ __('messages.property.status_booked') }}</option>
                        <option value="rented" {{ $property->status == 'rented' ? 'selected' : '' }}>{{ __('messages.property.status_rented') }}</option>
                        <option value="hidden" {{ $property->status == 'hidden' ? 'selected' : '' }}>{{ __('messages.property.status_hidden') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.sidebar.properties') }} (النوع)</label>
                    <select name="property_type_id" class="w-full border border-gray-300 rounded-xl p-3 shadow-sm">
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type->id }}" {{ $property->property_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.city_label') }}</label>
                    <input name="city" value="{{ old('city', $property->city) }}" class="w-full border border-gray-300 rounded-xl p-3 shadow-sm">
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.neighborhood_label') }}</label>
                    <input name="neighborhood" value="{{ old('neighborhood', $property->neighborhood) }}" class="w-full border border-gray-300 rounded-xl p-3 shadow-sm">
                </div>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.description_label') }}</label>
                <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-xl p-3 shadow-sm">{{ old('description', $property->description) }}</textarea>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">{{ __('messages.property.amenities_label') }}</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($amenities as $a)
                        <label class="inline-flex items-center bg-gray-50 border rounded-lg px-3 py-2 cursor-pointer">
                            <input type="checkbox" name="amenity_ids[]" value="{{ $a->id }}" {{ in_array($a->id, $property->amenities->pluck('id')->toArray()) ? 'checked' : '' }}>
                            <span class="ml-2 mr-2">{{ $a->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('dashboard.properties.index') }}" class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold">
                    {{ __('messages.property.cancel_button') }}
                </a>
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-lg hover:bg-indigo-700 transition">
                    {{ __('messages.property.save_button') }}
                </button>
            </div>
        </form>
    </div>
</div>


<script>
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
