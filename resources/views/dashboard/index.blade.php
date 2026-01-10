@extends('dashboard.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')


{{-- Outer: full viewport, no vertical scroll --}}
<div class="h-screen overflow-hidden flex flex-col p-6">

    {{-- ===== Stats (top) ===== --}}
    <div class="flex-none">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-indigo-50">
                <p class="text-sm text-gray-500 text-end">Total Properties</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalProperties ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-indigo-50">
                <p class="text-sm text-gray-500 text-end">Total Bookings</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBookings ?? 0 }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-yellow-100">
                <p class="text-sm text-yellow-600 text-end">Pending (last 6 months)</p>
                <p id="pendingCount" class="text-2xl font-bold text-yellow-700">—</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-green-100">
                <p class="text-sm text-green-600 text-end">Approved (last 6 months)</p>
                <p id="approvedCount" class="text-2xl font-bold text-green-700">—</p>
            </div>
        </div>
    </div>

    {{-- ===== Charts area: occupies remaining height ===== --}}
    <div class="flex-1 mt-6 flex flex-col">

        {{-- Two charts side-by-side (or stacked on small screens) — smaller heights to avoid vertical scroll --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Booking Status --}}
            <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-indigo-50 h-full flex flex-col">
                <div class="flex items-start justify-end mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-indigo-600">Booking Status Over Time</h3>
                        <p class="text-sm text-gray-500 text-end">Last 6 months</p>
                    </div>
                </div>

                <div>
                    <div class="h-64">
                        <canvas id="statusChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

            {{-- Properties per Month --}}
            <div class="bg-white p-6 rounded-xl shadow-sm ring-1 ring-indigo-50 h-full flex flex-col">
                <div class="flex items-start justify-end mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-indigo-600">Properties Per Month</h3>
                        <p class="text-sm text-gray-500 text-end">Last 6 months</p>
                    </div>
                </div>

                <div>
                    <div class="h-64">
                        <canvas id="propertiesChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- Decorative strip under charts (small, subtle, does not push page to scroll) --}}
        <div class="mt-4 flex-none">
            <div class="bg-gradient-to-r from-indigo-100 via-white to-indigo-50 rounded-2xl p-3 shadow-inner ring-1 ring-indigo-50">
                <div class="max-w-6xl p-10 mx-auto flex items-center justify-between gap-4">
                    {{-- Small card 1 --}}
                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[160px]">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">P</div>
                        <div>
                            <p class="text-xs text-gray-500">Total Properties</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $totalProperties ?? 0 }}</p>
                        </div>
                    </div>

                    {{-- Small card 2 --}}
                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[160px]">
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 font-semibold">B</div>
                        <div>
                            <p class="text-xs  text-gray-500">Total Bookings</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $totalBookings ?? 0 }}</p>
                        </div>
                    </div>

                    {{-- Small card 3: dynamic sums (will be filled by JS) --}}
                    <div id="miniStatDynamic" class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm min-w-[160px]">
                        <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 font-semibold">S</div>
                        <div>
                            <p class="text-xs text-gray-500">Pending (6m)</p>
                            <p id="miniPending" class="text-sm font-semibold text-gray-800">—</p>
                        </div>
                    </div>

                    {{-- Spacer to keep layout neat --}}
                    <div class="flex-1"></div>

                    {{-- Small decorative text --}}
                    <div class="text-xs text-gray-500">Dashboard • Preview data</div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

{{-- PHP → JS --}}
<script>
window.DASHBOARD = {
    status: @json($statusStats ?? []),
    propertiesPerMonth: @json($propertiesPerMonth ?? []),
    totals: {
        properties: {{ $totalProperties ?? 0 }},
        bookings: {{ $totalBookings ?? 0 }}
    }
};
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // defensive mapping (handle empty arrays)
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

    // ===== Mini stats (place into top cards + decorative strip) =====
    const pendingSum = statusRows.reduce((s,r) => s + r.pending, 0);
    const approvedSum = statusRows.reduce((s,r) => s + r.approved, 0);

    const elPendingTop = document.getElementById('pendingCount');
    const elApprovedTop = document.getElementById('approvedCount');
    const elMiniPending = document.getElementById('miniPending');

    if (elPendingTop) elPendingTop.textContent = pendingSum;
    if (elApprovedTop) elApprovedTop.textContent = approvedSum;
    if (elMiniPending) elMiniPending.textContent = pendingSum;

    // fallback labels (if empty)
    const defaultLabels = (window.DASHBOARD.months && window.DASHBOARD.months.length)
        ? window.DASHBOARD.months.map(m => new Date(m + '-01').toLocaleString('en', { month: 'short' }))
        : ['Jan','Feb','Mar','Apr','May','Jun'];

    // ===== Booking Status Chart =====
    (function () {
        const canvas = document.getElementById('statusChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        const labels = statusRows.length ? statusRows.map(r => r.month) : defaultLabels;
        const pending  = statusRows.length ? statusRows.map(r => r.pending)  : [0,0,0,0,0,0];
        const approved = statusRows.length ? statusRows.map(r => r.approved) : [0,0,0,0,0,0];
        const rejected = statusRows.length ? statusRows.map(r => r.rejected) : [0,0,0,0,0,0];

        // gradients sized to canvas
        const approvedGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        approvedGradient.addColorStop(0, 'rgba(79,70,229,0.18)');
        approvedGradient.addColorStop(1, 'rgba(79,70,229,0)');

        const pendingGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        pendingGradient.addColorStop(0, 'rgba(245,158,11,0.12)');
        pendingGradient.addColorStop(1, 'rgba(245,158,11,0)');

        const rejectedGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        rejectedGradient.addColorStop(0, 'rgba(239,68,68,0.10)');
        rejectedGradient.addColorStop(1, 'rgba(239,68,68,0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Pending',
                        data: pending,
                        borderColor: '#f59e0b',
                        backgroundColor: pendingGradient,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#f59e0b',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Approved',
                        data: approved,
                        borderColor: '#4f46e5',
                        backgroundColor: approvedGradient,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        borderWidth: 3,
                        fill: true
                    },
                    {
                        label: 'Rejected',
                        data: rejected,
                        borderColor: '#ef4444',
                        backgroundColor: rejectedGradient,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#ef4444',
                        pointBorderWidth: 2,
                        borderWidth: 2,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    x: { ticks: { color: '#6b7280' } },
                    y: { beginAtZero: true, ticks: { color: '#6b7280' } }
                }
            }
        });
    })();

    // ===== Properties Chart (LINE) =====
    (function () {
        const canvas = document.getElementById('propertiesChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        const labels = propertyRows.length ? propertyRows.map(r => r.month) : defaultLabels;
        const totals = propertyRows.length ? propertyRows.map(r => r.total) : [0,0,0,0,0,0];

        const fillGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        fillGradient.addColorStop(0, 'rgba(79,70,229,0.15)');
        fillGradient.addColorStop(1, 'rgba(79,70,229,0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Properties',
                        data: totals,
                        borderColor: '#4f46e5',
                        backgroundColor: fillGradient,
                        tension: 0.36,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#6b7280' } },
                    x: { ticks: { color: '#6b7280' } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    })();

});
</script>

@endsection
