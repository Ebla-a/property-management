@extends('dashboard.layout')

@section('styles')
<style>
/* ===== Chart Specific Formatting ===== */
.chart-container * {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
    text-transform: none !important;
}

.chart-axis text,
.axis text,
.x-axis text,
.y-axis text {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
    font-size: 12px !important;
    fill: #6c757d !important;
    letter-spacing: 0 !important;
    text-transform: none !important;
}

.chart-number {
    font-feature-settings: "tnum" !important;
    font-variant-numeric: tabular-nums !important;
    unicode-bidi: embed !important;
    direction: ltr !important;
}

.chart-tooltip {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
    direction: ltr !important;
    text-align: left !important;
}

.chart-legend {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
    text-transform: none !important;
    direction: ltr !important;
}

.chart-label {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
    text-transform: none !important;
    direction: ltr !important;
}

/* Fix for Chart.js */
.chartjs-render-monitor {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
}

.chartjs-axis text,
.chartjs-legend text {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
    text-transform: none !important;
}

/* Ensure all chart containers are LTR */
#weeklyChart, #monthlyChart {
    direction: ltr !important;
}

/* Force English numbers in all charts */
.chartjs-chart * {
    font-feature-settings: "tnum" !important;
    font-variant-numeric: tabular-nums !important;
}

/* Stats cards enhancement */
.bg-white.border.rounded-2xl {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.bg-white.border.rounded-2xl:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.text-3xl.font-bold {
    font-feature-settings: "tnum";
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.5px;
}

/* Table enhancement */
.min-w-full.table-auto {
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.min-w-full.table-auto th {
    font-weight: 600;
    color: #495057;
}

.min-w-full.table-auto td {
    color: #6c757d;
}

/* Status badges enhancement */
.rounded-full {
    font-weight: 600;
    font-size: 0.85rem;
}
</style>
@endsection

@section('content')
<div class="px-6 py-8" style="direction:ltr">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-6">
        {{-- Total --}}
        <div class="bg-white border rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Bookings</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
        </div>

        {{-- This Week --}}
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5">
            <p class="text-sm text-indigo-700">This Week</p>
            <p class="text-3xl font-bold text-indigo-800 mt-2">{{ $stats['this_week'] }}</p>
        </div>

        {{-- This Month --}}
        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-5">
            <p class="text-sm text-purple-700">This Month</p>
            <p class="text-3xl font-bold text-purple-800 mt-2">{{ $stats['this_month'] }}</p>
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

        {{-- Today --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
            <p class="text-sm text-blue-700">Today</p>
            <p class="text-3xl font-bold text-blue-800 mt-2">{{ $stats['today'] }}</p>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Weekly Chart --}}
        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Weekly Overview</h2>
                <span class="text-sm text-gray-500">{{ $weeklyData['title'] }}</span>
            </div>
            <div class="h-64">
                <canvas id="weeklyChart" dir="ltr"></canvas>
            </div>
            @if($weeklyData['total'] == 0)
                <div class="mt-4 text-center text-gray-500 text-sm">
                    <p>No bookings in the last 7 days</p>
                </div>
            @endif
        </div>
        
        {{-- Monthly Chart --}}
        <div class="bg-white border rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Monthly Overview</h2>
                <span class="text-sm text-gray-500">{{ $monthlyData['title'] }}</span>
            </div>
            <div class="h-64">
                <canvas id="monthlyChart" dir="ltr"></canvas>
            </div>
            @if($monthlyData['total'] == 0)
                <div class="mt-4 text-center text-gray-500 text-sm">
                    <p>No bookings this month yet</p>
                </div>
            @endif
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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // Force English fonts for all charts
    Chart.defaults.font.family = "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif";
    Chart.defaults.font.size = 12;
    
    const weeklyCtx = document.getElementById('weeklyChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: @json($weeklyData['labels']),
                datasets: [{
                    label: 'Daily Bookings',
                    data: @json($weeklyData['data']),
                    borderWidth: 3,
                    borderColor: 'rgba(79, 70, 229, 1)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                // Ensure numbers are displayed correctly
                                return value;
                            }
                        },
                        title: {
                            display: true,
                            text: 'Number of Bookings',
                            font: {
                                family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                            }
                        },
                        grid: {
                            display: true
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                            }
                        },
                        grid: {
                            display: true
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        titleFont: {
                            family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                        },
                        bodyFont: {
                            family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                        },
                        callbacks: {
                            label: function(context) {
                                return `Bookings: ${context.raw}`;
                            }
                        }
                    }
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
                    label: 'Bookings per Day',
                    data: @json($monthlyData['data']),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value;
                            }
                        },
                        title: {
                            display: true,
                            text: 'Number of Bookings',
                            font: {
                                family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                            }
                        },
                        grid: {
                            display: true
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Day of Month',
                            font: {
                                family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                            }
                        },
                        ticks: {
                            font: {
                                family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        titleFont: {
                            family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                        },
                        bodyFont: {
                            family: "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif"
                        },
                        callbacks: {
                            label: function(context) {
                                return `Bookings: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Additional fix for chart numbers display
    setTimeout(() => {
        document.querySelectorAll('canvas').forEach(canvas => {
            canvas.style.fontFamily = "'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif";
        });
    }, 100);
});
</script>
