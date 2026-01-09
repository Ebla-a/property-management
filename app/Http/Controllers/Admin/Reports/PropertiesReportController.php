<?php
namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertiesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.active', 'role:admin']);
    }

    public function index(Request $request)
    {
        // Validation
        $filters = $request->validate([
            'status' => 'nullable|in:available,booked,rented,hidden',
            'city'   => 'nullable|string',
            'from'   => 'nullable|date',
            'to'     => 'nullable|date',
        ]);

        $query = Property::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (!empty($filters['from']) && !empty($filters['to'])) {
            $query->whereBetween('created_at', [
                $filters['from'],
                $filters['to']
            ]);
        }

        $report = [
            'total_properties' => $query->count(),

            'by_status' => [
                'available' => Property::where('status', 'available')->count(),
                'booked'    => Property::where('status', 'booked')->count(),
                'rented'    => Property::where('status', 'rented')->count(),
                'hidden'    => Property::where('status', 'hidden')->count(),
            ],

            'properties' => $query->latest()->get(),
        ];

        return view('dashboard.reports.properties', compact('report'));
    }
}
