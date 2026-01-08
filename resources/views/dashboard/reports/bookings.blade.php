@extends('dashboard.layout')

@section('content')

<div class="lg:ml-50 px-6 py-8" style="direction:ltr">

    {{-- Page Title --}}
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">
            Bookings Report
        </h1>
    </div>


    {{-- ===== MAIN STATS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">

        @php
            $cards = [
                ['label' => 'Total Bookings', 'value' => $stats['total'], 'color' => 'text-gray-700'],
                ['label' => 'Pending', 'value' => $stats['pending'], 'color' => 'text-yellow-600'],
                ['label' => 'Approved', 'value' => $stats['approved'], 'color' => 'text-green-600'],
                ['label' => 'Completed', 'value' => $stats['completed'], 'color' => 'text-emerald-600'],
                ['label' => 'Cancelled', 'value' => $stats['canceled'], 'color' => 'text-gray-600'],
                ['label' => 'Rejected', 'value' => $stats['rejected'], 'color' => 'text-red-600'],
                ['label' => 'Rescheduled', 'value' => $stats['rescheduled'], 'color' => 'text-blue-600'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white border rounded-2xl shadow-sm px-5 py-4 hover:shadow-md transition">

            <p class="text-xs {{ $card['color'] }} tracking-wide">
                {{ $card['label'] }}
            </p>

            <h3 class="text-3xl font-semibold mt-1 leading-tight">
                {{ $card['value'] }}
            </h3>

        </div>
        @endforeach

    </div>


    {{-- ===== TIME STATS ===== --}}
    <div class="mt-10 mb-2 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-900">Time Based Statistics</h2>
        <span class="text-xs px-2 py-0.5 rounded-lg bg-gray-100 text-gray-600 border">
            Period summary
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-white border rounded-2xl shadow-sm px-5 py-4">
            <p class="text-xs text-gray-500">Today</p>
            <h3 class="text-3xl font-semibold mt-1">{{ $stats['today'] }}</h3>
        </div>

        <div class="bg-white border rounded-2xl shadow-sm px-5 py-4">
            <p class="text-xs text-gray-500">This Week</p>
            <h3 class="text-3xl font-semibold mt-1">{{ $stats['this_week'] }}</h3>
        </div>

        <div class="bg-white border rounded-2xl shadow-sm px-5 py-4">
            <p class="text-xs text-gray-500">This Month</p>
            <h3 class="text-3xl font-semibold mt-1">{{ $stats['this_month'] }}</h3>
        </div>

    </div>


    {{-- ===== TOP EMPLOYEES ===== --}}
    <div class="mt-10 mb-3 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-900">Top Employees</h2>
        <span class="text-xs px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
            Performance
        </span>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">Employee</th>
                    <th class="px-4 py-2 text-left font-medium">Bookings</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($stats['top_employees'] as $top_employee)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2">
                        {{ $top_employee->employee->name ?? 'Unknown' }}
                    </td>
                    <td class="px-4 py-2 font-semibold">
                        {{ $top_employee->total }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">
                        No data available
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>


    {{-- ===== BOOKINGS BY CITY ===== --}}
    <div class="mt-10 mb-3 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-900">Bookings by City</h2>
        <span class="text-xs px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
            Locations
        </span>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">City</th>
                    <th class="px-4 py-2 text-left font-medium">Total</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($stats['by_city'] as $city)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2">{{ $city->city ?? 'Unknown' }}</td>
                    <td class="px-4 py-2 font-semibold">{{ $city->total }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">
                        No data available
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

@endsection
