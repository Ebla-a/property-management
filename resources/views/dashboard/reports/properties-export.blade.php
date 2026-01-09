@extends('dashboard.layout')

@section('content')
<div class="px-6 py-8 space-y-8" style="direction:ltr">

    {{-- Page Title + Actions --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 pb-2">Properties Report</h1>
            <p class="text-sm text-gray-500">Generated at: {{ now()->format('Y-m-d H:i') }}</p>
        </div>

        {{-- Export Button --}}
        <a href="{{ route('dashboard.reports.properties.export', request()->all()) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium
                  bg-indigo-600 text-white
                  hover:bg-indigo-700 transition">
            Export Report
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET"
          class="bg-white border rounded-2xl p-5 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">All</option>
                <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>Available</option>
                <option value="booked" {{ request('status')=='booked' ? 'selected' : '' }}>Booked</option>
                <option value="rented" {{ request('status')=='rented' ? 'selected' : '' }}>Rented</option>
                <option value="hidden" {{ request('status')=='hidden' ? 'selected' : '' }}>Hidden</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
            <input type="text" name="city" value="{{ request('city') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="City name">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">From date</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">To date</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="md:col-span-4 flex justify-end">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                Apply Filters
            </button>
        </div>
    </form>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white border rounded-xl p-4 shadow-sm text-center">
            <p class="text-sm text-gray-500">Total Properties</p>
            <p class="text-2xl font-bold text-gray-900">{{ $report['total_properties'] }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-sm text-green-700">Available</p>
            <p class="text-2xl font-bold text-green-800">{{ $report['by_status']['available'] }}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
            <p class="text-sm text-yellow-700">Booked</p>
            <p class="text-2xl font-bold text-yellow-800">{{ $report['by_status']['booked'] }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <p class="text-sm text-blue-700">Rented</p>
            <p class="text-2xl font-bold text-blue-800">{{ $report['by_status']['rented'] }}</p>
        </div>
        <div class="bg-gray-100 border border-gray-300 rounded-xl p-4 text-center">
            <p class="text-sm text-gray-700">Hidden</p>
            <p class="text-2xl font-bold text-gray-800">{{ $report['by_status']['hidden'] }}</p>
        </div>
    </div>

    {{-- Properties Table --}}
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden mt-6">
        <div class="px-5 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Properties List</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">City</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Created At</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($report['properties'] as $property)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $property->title }}</td>
                            <td class="px-4 py-3">{{ $property->city }}</td>
                            <td class="px-4 py-3 capitalize">{{ $property->status }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $property->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                No properties found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
