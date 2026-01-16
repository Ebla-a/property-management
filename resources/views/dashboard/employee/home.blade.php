@extends('dashboard.layout')

@section('styles')
<style>
.chart-container * { font-family: 'Segoe UI', 'Roboto', sans-serif !important; }
.chart-number { font-feature-settings: "tnum" !important; direction: ltr !important; }
.bg-white.border.rounded-2xl { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.bg-white.border.rounded-2xl:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
</style>
@endsection

@section('content')
@php $isRtl = app()->getLocale() == 'ar'; @endphp

<div class="lg:ms-50 px-6 py-8" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-6">
        <div class="bg-white border rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">{{ __('messages.dashboard.total_bookings') }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5">
            <p class="text-sm text-indigo-700">{{ __('messages.dashboard.this_week') }}</p>
            <p class="text-3xl font-bold text-indigo-800 mt-2">{{ $stats['this_week'] }}</p>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-5">
            <p class="text-sm text-purple-700">{{ __('messages.dashboard.this_month') }}</p>
            <p class="text-3xl font-bold text-purple-800 mt-2">{{ $stats['this_month'] }}</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
            <p class="text-sm text-yellow-700">{{ __('messages.dashboard.pending') }}</p>
            <p class="text-3xl font-bold text-yellow-800 mt-2">{{ $stats['pending'] }}</p>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <p class="text-sm text-green-700">{{ __('messages.dashboard.approved') }}</p>
            <p class="text-3xl font-bold text-green-800 mt-2">{{ $stats['approved'] }}</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
            <p class="text-sm text-blue-700">{{ __('messages.dashboard.today') }}</p>
            <p class="text-3xl font-bold text-blue-800 mt-2">{{ $stats['today'] }}</p>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('messages.dashboard.weekly_overview') }}</h2>
                <span class="text-sm text-gray-500" dir="ltr">{{ $weeklyData['title'] }}</span>
            </div>
            <div class="h-64">
                <canvas id="weeklyChart" dir="ltr"></canvas>
            </div>
        </div>
        
        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('messages.dashboard.monthly_overview') }}</h2>
                <span class="text-sm text-gray-500" dir="ltr">{{ $monthlyData['title'] }}</span>
            </div>
            <div class="h-64">
                <canvas id="monthlyChart" dir="ltr"></canvas>
            </div>
        </div>
    </div>

    {{-- Latest Bookings --}}
    <div class="mt-10 bg-white border rounded-2xl p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('messages.dashboard.latest_bookings') }}</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 {{ $isRtl ? 'text-right' : 'text-left' }} text-sm font-medium text-gray-500">{{ __('messages.dashboard.id') }}</th>
                        <th class="px-4 py-2 {{ $isRtl ? 'text-right' : 'text-left' }} text-sm font-medium text-gray-500">{{ __('messages.dashboard.user') }}</th>
                        <th class="px-4 py-2 {{ $isRtl ? 'text-right' : 'text-left' }} text-sm font-medium text-gray-500">{{ __('messages.dashboard.property') }}</th>
                        <th class="px-4 py-2 {{ $isRtl ? 'text-right' : 'text-left' }} text-sm font-medium text-gray-500">{{ __('messages.dashboard.status') }}</th>
                        <th class="px-4 py-2 {{ $isRtl ? 'text-right' : 'text-left' }} text-sm font-medium text-gray-500">{{ __('messages.dashboard.scheduled_at') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($latestBookings as $booking)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $booking->id }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $booking->user->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $booking->property->title }}</td>
                        <td class="px-4 py-2 text-sm">
                             <span class="px-2 py-1 rounded-full text-xs font-medium 
                                @if($booking->status == 'pending') bg-yellow-100 text-yellow-800 
                                @elseif($booking->status == 'approved') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ __("messages.status.{$booking->status}") }}
                             </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700" dir="ltr">{{ $booking->scheduled_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // نصوص مترجمة للرسوم البيانية
    const chartLabels = {
        daily: "{{ __('messages.dashboard.daily_bookings') }}",
        count: "{{ __('messages.dashboard.booking_count') }}",
        dayOfMonth: "{{ __('messages.dashboard.day_of_month') }}"
    };

    const weeklyCtx = document.getElementById('weeklyChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: @json($weeklyData['labels']),
                datasets: [{
                    label: chartLabels.daily,
                    data: @json($weeklyData['data']),
                    borderColor: 'rgba(79, 70, 229, 1)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { title: { display: true, text: chartLabels.count } }
                }
            }
        });
    }

    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyData['labels']),
                datasets: [{
                    label: chartLabels.daily,
                    data: @json($monthlyData['data']),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { title: { display: true, text: chartLabels.count } },
                    x: { title: { display: true, text: chartLabels.dayOfMonth } }
                }
            }
        });
    }
});
</script>
@endsection