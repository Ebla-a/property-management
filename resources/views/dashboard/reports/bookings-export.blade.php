<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bookings Report</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 15px; margin-bottom: 30px; }
        .header h1 { color: #1e1b4b; margin: 0; font-size: 24px; }
        .header p { color: #6b7280; font-size: 12px; margin-top: 5px; }
        
        .section-title { font-size: 16px; font-weight: bold; color: #4f46e5; margin-bottom: 10px; border-left: 4px solid #4f46e5; padding-left: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 13px; }
        th { background-color: #f9fafb; color: #374151; font-weight: bold; text-align: left; border: 1px solid #e5e7eb; padding: 10px; }
        td { border: 1px solid #e5e7eb; padding: 10px; color: #4b5563; }
        .bg-light { background-color: #fbfbfb; }
        
        .stats-grid { width: 100%; margin-bottom: 20px; }
        .stats-cell { width: 25%; border: 1px solid #e5e7eb; padding: 15px; text-align: center; }
        .stats-label { font-size: 11px; color: #6b7280; text-transform: uppercase; margin-bottom: 5px; }
        .stats-value { font-size: 18px; font-weight: bold; color: #111827; }

        footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #9ca3af; padding: 10px 0; border-top: 1px solid #eee; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Bookings Report</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <div class="section-title">Overview Statistics</div>
    <table class="stats-grid">
        <tr>
            <td class="stats-cell">
                <div class="stats-label">Total Bookings</div>
                <div class="stats-value">{{ $stats['total'] }}</div>
            </td>
            <td class="stats-cell">
                <div class="stats-label">Pending</div>
                <div class="stats-value">{{ $stats['pending'] }}</div>
            </td>
            <td class="stats-cell">
                <div class="stats-label">Approved</div>
                <div class="stats-value">{{ $stats['approved'] }}</div>
            </td>
            <td class="stats-cell">
                <div class="stats-label">Completed</div>
                <div class="stats-value">{{ $stats['completed'] }}</div>
            </td>
        </tr>
    </table>

    <table class="w-full">
        <thead>
            <tr>
                <th>Status Name</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Canceled</td><td>{{ $stats['canceled'] }}</td></tr>
            <tr class="bg-light"><td>Rejected</td><td>{{ $stats['rejected'] }}</td></tr>
            <tr><td>Rescheduled</td><td>{{ $stats['rescheduled'] }}</td></tr>
        </tbody>
    </table>

    <div class="section-title">Top Performance Employees</div>
    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Number of Bookings</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['top_employees'] as $emp)
                <tr class="{{ $loop->even ? 'bg-light' : '' }}">
                    <td>{{ $emp->employee->name ?? 'N/A' }}</td>
                    <td>{{ $emp->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Distribution by City</div>
    <table>
        <thead>
            <tr>
                <th>City</th>
                <th>Total Bookings</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['by_city'] as $city)
                <tr class="{{ $loop->even ? 'bg-light' : '' }}">
                    <td>{{ $city->city }}</td>
                    <td>{{ $city->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Property Management System | &copy; {{ date('Y') }}
    </footer>

</body>
</html>