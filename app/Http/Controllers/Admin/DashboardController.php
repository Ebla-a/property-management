<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $months = collect(range(5, 0))
            ->map(fn($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->values();
        $rawBooking = Booking::select(
                DB::raw("DATE_FORMAT(scheduled_at, '%Y-%m') as month"),
                DB::raw("SUM(status = 'pending') as pending"),
                DB::raw("SUM(status = 'approved') as approved"),
                DB::raw("SUM(status = 'rejected') as rejected")
            )
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        $statusStats = $months->map(function($m) use ($rawBooking) {
            $row = $rawBooking->get($m);
            return [
                'month_key' => $m,
                'month'     => Carbon::createFromFormat('Y-m', $m)->format('M'),
                'pending'   => (int) ($row->pending ?? 0),
                'approved'  => (int) ($row->approved ?? 0),
                'rejected'  => (int) ($row->rejected ?? 0),
            ];
        })->values();

        $rawProps = Property::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $propertiesPerMonth = $months->map(function($m) use ($rawProps) {
            $r = $rawProps->get($m);
            return [
                'month_key' => $m,
                'month'     => Carbon::createFromFormat('Y-m', $m)->format('M'),
                'total'     => (int) ($r->total ?? 0),
            ];
        })->values();
        $totalProperties = Property::count();
        $totalBookings = Booking::count();

        return view('dashboard.index', compact(
            'months',
            'statusStats',
            'propertiesPerMonth',
            'totalProperties',
            'totalBookings'
        ));
    }
}
