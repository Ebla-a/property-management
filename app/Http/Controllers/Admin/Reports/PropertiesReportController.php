<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Http\Request;

class PropertiesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.active', 'role:admin']);
    }

    public function index(Request $request)
    {

        $report = $this->getReportData($request);

        return view('dashboard.reports.properties', compact('report'));
    }

    public function export(Request $request)
    {

        $report = $this->getReportData($request);

        $pdf = PDF::loadView('dashboard.reports.properties-export', compact('report'));

        $fileName = 'properties_report_'.now()->format('Y-m-d_H-i-s').'.pdf';

        return $pdf->download($fileName);

    }

    public function getReportData(Request $request)
    {
        // Validation
        $filters = $request->validate([
            'status' => 'nullable|in:available,booked,rented,hidden',
            'city' => 'nullable|string',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $query = Property::query()
            ->when($filters['status'] ?? null, function ($q, $status) {
                $q->where('status', $status);
            })
            ->when($filters['city'] ?? null, function ($q, $city) {
                $q->where('city', $city);
            })
            ->when(
                ! empty($filters['from']) && ! empty($filters['to']),
                function ($q) use ($filters) {
                    $q->whereBetween('created_at', [
                        $filters['from'],
                        $filters['to'],
                    ]);
                }
            );

        return [
            'total_properties' => $query->count(),

            'by_status' => [
                'available' => Property::where('status', 'available')->count(),
                'booked' => Property::where('status', 'booked')->count(),
                'rented' => Property::where('status', 'rented')->count(),
                'hidden' => Property::where('status', 'hidden')->count(),
            ],

            'properties' => $query->latest()->get(),
        ];
    }
}
