@extends('dashboard.layout')


@section('content')
<div class="px-6 py-8" style="direction:ltr">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-6">
        {{-- Total --}}
        <div class="bg-white border rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Bookings</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
        </div>

        {{-- Pending --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
            <p class="text-sm text-yellow-700">Pending</p>
            <p class="text-3xl font-bold text-yellow-800 mt-2">{{ $stats['pending'] }}</p>
        </div>

        {{-- Approved --}}
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <p class="text-sm text-green-700">Approved</p>
            <p class="text-3xl font-bold text-green-800 mt-2">{{ $stats['approved'] }}</p>
        </div>

        {{-- Completed --}}
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
            <p class="text-sm text-emerald-700">Completed</p>
            <p class="text-3xl font-bold text-emerald-800 mt-2">{{ $stats['completed'] }}</p>
        </div>

        {{-- Today --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
            <p class="text-sm text-blue-700">Today's Bookings</p>
            <p class="text-3xl font-bold text-blue-800 mt-2">{{ $stats['today'] }}</p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="mt-10 bg-white border rounded-2xl p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Bookings Overview</h2>
        <div class="h-64">
    <canvas id="bookingsChart"></canvas>
</div>
    </div>

    {{-- Latest Bookings --}}
    <div class="mt-10 bg-white border rounded-2xl p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Latest Bookings</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">ID</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">User</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Property</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Scheduled At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($latestBookings as $booking)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $booking->id }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $booking->user->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $booking->property->title }}</td>
                        <td class="px-4 py-2 text-sm">
                            @if($booking->status == 'pending')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($booking->status) }}</span>
                            @elseif($booking->status == 'approved')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800">{{ ucfirst($booking->status) }}</span>
                            @elseif($booking->status == 'completed')
                                <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $booking->scheduled_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('bookingsChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($weekLabels),
            datasets: [{
                label: 'Bookings per Day',
                data: @json($weekData),

                borderWidth: 2,
                borderColor: 'rgba(34,197,94,1)',
                backgroundColor: 'rgba(34,197,94,0.15)',

                fill: true,
                tension: 0.3,

                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgba(34,197,94,1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },

            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

});
</script>
@endsection
