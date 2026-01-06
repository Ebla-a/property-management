@extends('dashboard.layout')

@section('title', 'Amenities')
@section('page_title', 'Amenities')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold">Amenities List</h3>

    <a href="{{ route('dashboard.amenities.create') }}"
        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
        + Add Amenity
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden" dir="ltr">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 font-semibold text-gray-700 w-16">#</th>
                <th class="px-6 py-3 font-semibold text-gray-700">Name</th>
                <th class="px-6 py-3 font-semibold text-gray-700 text-right w-40">Actions</th>
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

                <td class="px-6 py-3 text-right">
                    <div class="inline-flex gap-2">
                        <a href="{{ route('dashboard.amenities.edit', $amenity) }}"
                            class="px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            Edit
                        </a>

                        <form action="{{ route('dashboard.amenities.destroy', $amenity) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                    No amenities found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection