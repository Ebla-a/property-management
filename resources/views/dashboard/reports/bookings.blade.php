@extends('dashboard.layout')

@section('content')
@php $isRtl = app()->getLocale() == 'ar'; @endphp

<div class="lg:ms-50 px-6 py-8" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    {{-- Page Title + Actions --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 pb-2">
                {{ __('messages.reports.bookings_report') }}
            </h1>
            <p class="text-xs text-gray-500">
                {{ __('messages.reports.generated_at') }}: {{ now()->format('Y-m-d H:i') }}
            </p>
        </div>

        {{-- Export Button --}}
        <a href="{{ route('dashboard.reports.bookings.export') }}"
           class="px-4 py-2 rounded-xl text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm">
            {{ __('messages.reports.export') }}
        </a>
    </div>

    {{-- ===== MAIN STATS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
        @php
            $cards = [
                ['label' => __('messages.reports.total_bookings'), 'value' => $stats['total'], 'color' => 'text-gray-700'],
                ['label' => __('messages.booking.status.pending'), 'value' => $stats['pending'], 'color' => 'text-yellow-600'],
                ['label' => __('messages.booking.status.approved'), 'value' => $stats['approved'], 'color' => 'text-green-600'],
                ['label' => __('messages.booking.status.completed'), 'value' => $stats['completed'], 'color' => 'text-emerald-600'],
                ['label' => __('messages.booking.status.cancelled'), 'value' => $stats['canceled'], 'color' => 'text-gray-600'],
                ['label' => __('messages.booking.status.rejected'), 'value' => $stats['rejected'], 'color' => 'text-red-600'],
                ['label' => __('messages.booking.status.rescheduled'), 'value' => $stats['rescheduled'], 'color' => 'text-blue-600'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white border rounded-2xl shadow-sm px-5 py-4 hover:shadow-md transition">
            <p class="text-xs {{ $card['color'] }} tracking-wide font-medium">
                {{ $card['label'] }}
            </p>
            <h3 class="text-3xl font-semibold mt-1 leading-tight text-gray-900">
                {{ $card['value'] }}
            </h3>
        </div>
        @endforeach
    </div>

    {{-- ===== TIME STATS ===== --}}
    <div class="mt-10 mb-2 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.reports.time_stats') }}</h2>
        <span class="text-xs px-2 py-0.5 rounded-lg bg-gray-100 text-gray-600 border">
            {{ __('messages.reports.period_summary') }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white border rounded-2xl shadow-sm px-5 py-4 hover:shadow-sm transition">
            <p class="text-xs text-gray-500 font-medium">{{ __('messages.reports.today') }}</p>
            <h3 class="text-3xl font-semibold mt-1 text-gray-900">{{ $stats['today'] }}</h3>
        </div>

        <div class="bg-white border rounded-2xl shadow-sm px-5 py-4 hover:shadow-sm transition">
            <p class="text-xs text-gray-500 font-medium">{{ __('messages.reports.this_week') }}</p>
            <h3 class="text-3xl font-semibold mt-1 text-gray-900">{{ $stats['this_week'] }}</h3>
        </div>

        <div class="bg-white border rounded-2xl shadow-sm px-5 py-4 hover:shadow-sm transition">
            <p class="text-xs text-gray-500 font-medium">{{ __('messages.reports.this_month') }}</p>
            <h3 class="text-3xl font-semibold mt-1 text-gray-900">{{ $stats['this_month'] }}</h3>
        </div>
    </div>

    {{-- ===== TOP EMPLOYEES ===== --}}
    <div class="mt-10 mb-3 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.reports.top_employees') }}</h2>
        <span class="text-xs px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200">
            {{ __('messages.reports.performance') }}
        </span>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium">{{ __('messages.reports.employee') }}</th>
                    <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium">{{ __('messages.reports.bookings') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($stats['top_employees'] as $top_employee)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">{{ $top_employee->employee->name ?? __('messages.reports.unknown') }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $top_employee->total }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">{{ __('messages.reports.no_data') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== BOOKINGS BY CITY ===== --}}
    <div class="mt-10 mb-3 flex items-center gap-2">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.reports.bookings_by_city') }}</h2>
        <span class="text-xs px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
            {{ __('messages.reports.locations') }}
        </span>
    </div>

    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium">{{ __('messages.reports.city') }}</th>
                    <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium">{{ __('messages.reports.total') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($stats['by_city'] as $city)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">{{ $city->city ?? __('messages.reports.unknown') }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $city->total }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">{{ __('messages.reports.no_data') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection