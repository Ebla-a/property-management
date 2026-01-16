@extends('dashboard.layout')

@section('content')
@php $isRtl = app()->getLocale() == 'ar'; @endphp

<div class="lg:ms-50 px-6 py-8 space-y-8" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    {{-- Page Title + Actions --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 pb-2">
                {{ __('messages.sidebar.properties_report') }}
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

    {{-- Filters --}}
    <form method="GET" class="bg-white border rounded-2xl p-5 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.property.status') }}</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">{{ __('messages.reports.all') }}</option>
                <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>{{ __('messages.property.status_list.available') }}</option>
                <option value="booked" {{ request('status')=='booked' ? 'selected' : '' }}>{{ __('messages.property.status_list.booked') }}</option>
                <option value="rented" {{ request('status')=='rented' ? 'selected' : '' }}>{{ __('messages.property.status_list.rented') }}</option>
                <option value="hidden" {{ request('status')=='hidden' ? 'selected' : '' }}>{{ __('messages.property.status_list.hidden') }}</option>
            </select>
        </div>

        {{-- City --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.property.city') }}</label>
            <input type="text" name="city" value="{{ request('city') }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 outline-none focus:ring-1 focus:ring-indigo-500" placeholder="{{ __('messages.property.city_placeholder') }}">
        </div>

        {{-- From Date --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.reports.from_date') }}</label>
            <input type="date" name="from" value="{{ request('from') }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50">
        </div>

        {{-- To Date --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.reports.to_date') }}</label>
            <input type="date" name="to" value="{{ request('to') }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50">
        </div>

        <div class="md:col-span-4 flex {{ $isRtl ? 'justify-start' : 'justify-end' }}">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition">
                {{ __('messages.reports.apply_filters') }}
            </button>
        </div>
    </form>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white border rounded-xl p-4 shadow-sm text-center">
            <p class="text-xs text-gray-500 font-medium mb-1">{{ __('messages.reports.total_properties') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $report['total_properties'] }}</p>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-xs text-green-700 font-medium mb-1">{{ __('messages.property.status_list.available') }}</p>
            <p class="text-2xl font-bold text-green-800">{{ $report['by_status']['available'] ?? 0 }}</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-center">
            <p class="text-xs text-yellow-700 font-medium mb-1">{{ __('messages.property.status_list.booked') }}</p>
            <p class="text-2xl font-bold text-yellow-800">{{ $report['by_status']['booked'] ?? 0 }}</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <p class="text-xs text-blue-700 font-medium mb-1">{{ __('messages.property.status_list.rented') }}</p>
            <p class="text-2xl font-bold text-blue-800">{{ $report['by_status']['rented'] ?? 0 }}</p>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
            <p class="text-xs text-gray-600 font-medium mb-1">{{ __('messages.property.status_list.hidden') }}</p>
            <p class="text-2xl font-bold text-gray-800">{{ $report['by_status']['hidden'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Properties Table --}}
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50/50">
            <h2 class="font-semibold text-gray-800">{{ __('messages.property.list_properties') }}</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium">{{ __('messages.property.title') }}</th>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium">{{ __('messages.property.city') }}</th>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium">{{ __('messages.property.status') }}</th>
                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} font-medium">{{ __('messages.reports.created_at') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($report['properties'] as $property)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $property->title }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $property->city }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-md text-xs font-medium 
                                    {{ $property->status == 'available' ? 'bg-green-100 text-green-700' : 
                                       ($property->status == 'booked' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ __('messages.property.status_list.' . $property->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $property->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                {{ __('messages.property.no_properties') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection