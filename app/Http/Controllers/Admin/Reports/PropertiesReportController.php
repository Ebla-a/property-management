<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Log;

/**
 * PropertiesReportController
 * * Handles property analytics, data aggregation, and PDF report exportation.
 */
class PropertiesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.active', 'role:admin']);
    }

    /**
     * Display the property report dashboard.
     * * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $report = $this->getStats();
            return view('dashboard.reports.properties', compact('report'));
        } catch (\Exception $e) {
            Log::error('Error loading properties report: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading the report.');
        }
    }

    /**
     * Aggregate property statistics including status counts, location data, and time-based metrics.
     * * @return array
     */
    public function getStats()
    {
        try {
            return [
                // Matches $report['total_properties'] in Blade
                'total_properties' => Property::count(),
                
                // Matches $report['by_status'][...] in Blade
                'by_status' => [
                    'available' => Property::where('status', 'available')->count(),
                    'booked'    => Property::where('status', 'booked')->count(),
                    'rented'    => Property::where('status', 'rented')->count(),
                    'hidden'    => Property::where('status', 'hidden')->count(),
                ],

                // TIME BASED COUNTS
                'today' => Property::whereDate('created_at', today())->count(),
                'this_week' => Property::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'this_month' => Property::whereMonth('created_at', now()->month)->count(),

                // TOP EMPLOYEES
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
            
            // Return default structure on error to prevent view crashes
            return [
                'total_properties' => 0,
                'by_status' => [
                    'available' => 0,
                    'booked' => 0,
                    'rented' => 0,
                    'hidden' => 0,
                ],
                'today' => 0,
                'this_week' => 0,
                'this_month' => 0,
                'top_employees' => collect(),
                'by_city' => collect(),
                'properties' => collect(),
            ];
        }
    }

    /**
     * Generate and download a PDF version of the property report.
     * * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function export()
    {
        try {
            $report = $this->getStats();
            $pdf = PDF::loadView('dashboard.reports.properties-export', compact('report'));
            $fileName = 'properties_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('Error exporting properties report PDF: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while generating the PDF.');
        }
    }
}