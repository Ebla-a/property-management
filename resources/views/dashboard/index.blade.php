@extends('dashboard.layout')

@section('title', __('messages.dashboard.title'))
@section('page_title', __('messages.dashboard.page_title'))

@section('content')
@hasrole('admin')

{{-- Outer: full viewport, no vertical scroll --}}
<div class="h-screen overflow-hidden flex flex-col p-6" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- ===== Stats (top) ===== --}}
    <div class="flex-none">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-indigo-50">
                <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">{{ __('messages.dashboard.total_properties') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalProperties ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-indigo-50">
                <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">{{ __('messages.dashboard.total_bookings') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBookings ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-yellow-100">
                <p class="text-sm text-yellow-600 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">{{ __('messages.dashboard.pending_last_6m') }}</p>
                <p id="pendingCount" class="text-2xl font-bold text-yellow-700">—</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-green-100">
                <p class="text-sm text-green-600 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">{{ __('messages.dashboard.approved_last_6m') }}</p>
                <p id="approvedCount" class="text-2xl font-bold text-green-700">—</p>
            </div>
        </div>
    </div>

    {{-- ===== Charts area ===== --}}
    <div class="flex-1 mt-6 flex flex-col">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Booking Status --}}
            <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-indigo-50 h-full flex flex-col">
                <div class="flex items-start {{ app()->getLocale() == 'ar' ? 'justify-start' : 'justify-end' }} mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-indigo-600">{{ __('messages.dashboard.booking_status_over_time') }}</h3>
                        <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">{{ __('messages.dashboard.last_6_months') }}</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="statusChart" class="w-full h-full"></canvas>
                </div>
            </div>

            {{-- Properties per Month --}}
            <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-indigo-50 h-full flex flex-col">
                <div class="flex items-start {{ app()->getLocale() == 'ar' ? 'justify-start' : 'justify-end' }} mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-indigo-600">{{ __('messages.dashboard.properties_per_month') }}</h3>
                        <p class="text-sm text-gray-500 {{ app()->getLocale() == 'ar' ? 'text-start' : 'text-end' }}">{{ __('messages.dashboard.last_6_months') }}</p>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="propertiesChart" class="w-full h-full"></canvas>
                </div>
            </div>

        </div>

        {{-- Decorative strip --}}
        <div class="mt-4 flex-none">
            <div class="bg-gradient-to-r from-indigo-100 via-white to-indigo-50 rounded-2xl p-3 shadow-inner ring-1 ring-indigo-50">
                <div class="max-w-6xl p-4 mx-auto flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[160px]">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">P</div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('messages.dashboard.total_properties') }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $totalProperties ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[160px]">
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 font-semibold">B</div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('messages.dashboard.total_bookings') }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $totalBookings ?? 0 }}</p>
                        </div>
                    </div>

                    <div id="miniStatDynamic" class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[160px]">
                        <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 font-semibold">S</div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('messages.dashboard.pending_6m') }}</p>
                            <p id="miniPending" class="text-sm font-semibold text-gray-800">—</p>
                        </div>
                    </div>

                    <div class="flex-1"></div>
                    <div class="text-xs text-gray-500">{{ __('messages.dashboard.dashboard_preview') }}</div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
window.DASHBOARD = {
    status: @json($statusStats ?? []),
    propertiesPerMonth: @json($propertiesPerMonth ?? []),
    translations: {
        pending: "{{ __('messages.reports.pending') }}",
        approved: "{{ __('messages.reports.approved') }}",
        rejected: "{{ __('messages.reports.rejected') }}",
        properties: "{{ __('messages.sidebar.properties') }}"
    }
};

document.addEventListener('DOMContentLoaded', () => {
    const statusRows = (window.DASHBOARD.status || []).map(r => ({
        month: r.month ?? r.month_key ?? '',
        pending: Number(r.pending ?? 0),
        approved: Number(r.approved ?? 0),
        rejected: Number(r.rejected ?? 0)
    }));

    const propertyRows = (window.DASHBOARD.propertiesPerMonth || []).map(r => ({
        month: r.month ?? r.month_key ?? '',
        total: Number(r.total ?? 0)
    }));

    const pendingSum = statusRows.reduce((s,r) => s + r.pending, 0);
    const approvedSum = statusRows.reduce((s,r) => s + r.approved, 0);

    document.getElementById('pendingCount').textContent = pendingSum;
    document.getElementById('approvedCount').textContent = approvedSum;
    document.getElementById('miniPending').textContent = pendingSum;

    const labels = statusRows.map(r => r.month);

    // Booking Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: window.DASHBOARD.translations.pending,
                    data: statusRows.map(r => r.pending),
                    borderColor: '#f59e0b',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(245,158,11,0.1)'
                },
                {
                    label: window.DASHBOARD.translations.approved,
                    data: statusRows.map(r => r.approved),
                    borderColor: '#4f46e5',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(79,70,229,0.1)'
                },
                {
                    label: window.DASHBOARD.translations.rejected,
                    data: statusRows.map(r => r.rejected),
                    borderColor: '#ef4444',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(239,68,68,0.1)'
                }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Properties Chart
    const propCtx = document.getElementById('propertiesChart').getContext('2d');
    new Chart(propCtx, {
        type: 'line',
        data: {
            labels: propertyRows.map(r => r.month),
            datasets: [{
                label: window.DASHBOARD.translations.properties,
                data: propertyRows.map(r => r.total),
                borderColor: '#4f46e5',
                tension: 0.36,
                fill: true,
                backgroundColor: 'rgba(79,70,229,0.1)'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
@endhasrole
@endsection