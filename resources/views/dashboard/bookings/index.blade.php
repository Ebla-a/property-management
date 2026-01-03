@extends('dashboard.layout')

@section('content')

<h2 class="text-xl font-bold mb-4">
    Bookings Management
</h2>

@if($bookings->count())

<table class="table-auto w-full">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->id }}</td>
            <td>{{ $booking->customer_name ?? '-' }}</td>
            <td>{{ $booking->status }}</td>
            <td>{{ $booking->created_at->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $bookings->links() }}

@else

<p>No bookings found.</p>

@endif

@endsection
