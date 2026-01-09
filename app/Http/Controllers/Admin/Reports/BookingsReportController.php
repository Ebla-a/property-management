<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingsReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.active', 'role:admin']);
    }


public function index()
{


        $stats = [

            // BASIC COUNTS 
            'total'      => Booking::count(),
            'pending'    => Booking::where('status', 'pending')->count(),
            'approved'   => Booking::where('status', 'approved')->count(),
            'canceled'   => Booking::where('status', 'canceled')->count(),
            'completed'  => Booking::where('status', 'completed')->count(),
            'rescheduled'=> Booking::where('status', 'rescheduled')->count(),
            'rejected'   => Booking::where('status', 'rejected')->count(),
            

            //  TIME BASED 
            'today' => Booking::whereDate('created_at', today())->count(),

            'this_week' => Booking::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),

            'this_month' => Booking::whereMonth('created_at', now()->month())->count(),


            // TOP EMPLOYEES 
            'top_employees' => Booking::selectRaw('employee_id, COUNT(*) as total')
                ->whereNotNull('employee_id')
                ->groupBy('employee_id')
                ->with('employee:id,name')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),


            // BOOKINGS BY CITY 
            'by_city' => Booking::selectRaw('properties.city, COUNT(*) as total')
                ->join('properties', 'bookings.property_id', '=', 'properties.id')
                ->groupBy('properties.city')
                ->get(),
        ];

        return view('dashboard.reports.bookings', compact('stats' ));
    }
}
