@extends('dashboard.layout')

@section('title', 'Edit Amenity')
@section('page_title', 'Edit Amenity')

@section('content')
<div class="max-w-full bg-white p-6 rounded-xl shadow" dir="ltr">
    <form action="{{ route('dashboard.amenities.update', $amenity) }}"
        method="POST"
        class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">
                Amenity Name
            </label>
            <input type="text"
                name="name"
                value="{{ old('name', $amenity->name) }}"
                class="mt-1 w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                required>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('dashboard.amenities.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Cancel
            </a>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Update
            </button>
        </div>
    </form>
</div>
@endsection