<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Log;

class PropertiesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.active', 'role:admin']);
    }

    // VIEW THE REPORT PAGE
    public function index()
    {
        try {
            $stats = $this->getStats();
            return view('dashboard.reports.properties', compact('stats'));
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error loading properties report: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading the report.');
        }
    }

    // GET ALL THE STATS
    public function getStats()
    {
        try {
            return [
                // BASIC COUNTS
                'total' => Property::count(),
                'available' => Property::where('status', 'available')->count(),
                'booked'    => Property::where('status', 'booked')->count(),
                'rented'    => Property::where('status', 'rented')->count(),
                'hidden'    => Property::where('status', 'hidden')->count(),

                // TIME BASED COUNTS
                'today' => Property::whereDate('created_at', today())->count(),
                'this_week' => Property::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'this_month' => Property::whereMonth('created_at', now()->month)->count(),

                // TOP EMPLOYEES (optional if properties have employee relation)
                'top_employees' => Property::selectRaw('employee_id, COUNT(*) as total')
                    ->whereNotNull('employee_id')
                    ->groupBy('employee_id')
                    ->with('employee:id,name')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->get(),

                // PROPERTIES BY CITY
                'by_city' => Property::selectRaw('city, COUNT(*) as total')
                    ->groupBy('city')
                    ->get(),

                // LIST OF PROPERTIES
                'properties' => Property::latest()->get(),
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching properties stats: ' . $e->getMessage());
            // Return empty stats in case of error
            return [
                'total' => 0,
                'available' => 0,
                'booked' => 0,
                'rented' => 0,
                'hidden' => 0,
                'today' => 0,
                'this_week' => 0,
                'this_month' => 0,
                'top_employees' => collect(),
                'by_city' => collect(),
                'properties' => collect(),
            ];
        }
    }

    // EXPORT THE REPORT AS PDF
    public function export()
    {
        try {
            $stats = $this->getStats();
            $pdf = PDF::loadView('dashboard.reports.properties-export', compact('stats'));
            $fileName = 'properties_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('Error exporting properties report PDF: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while generating the PDF.');
        }
    }
}
