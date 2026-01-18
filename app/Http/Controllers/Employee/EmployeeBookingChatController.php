<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class EmployeeBookingChatController extends Controller
{
    // Show the chat interface
    public function index()
    {
        // مثال: نجيب الحجوزات الخاصة بالموظف الحالي
        $reservations = Booking::with('customer')
            ->where('employee_id', Auth::id())
            ->get()
            ->map(function ($booking) {
                return (object) [
                    'id' => $booking->id,
                    'customer_name' => $booking->customer->name ?? 'N/A',
                    'date' => $booking->scheduled_at->format('Y-m-d H:i'),
                    'short_note' => $booking->notes ? substr($booking->notes, 0, 30) : '',
                ];
            });

        return view('dashboard.bookings.chat', [
            'reservations' => $reservations,
            'employeeId' => Auth::id(),
            'employeeName' => Auth::user()->name,
        ]);
    }
}
