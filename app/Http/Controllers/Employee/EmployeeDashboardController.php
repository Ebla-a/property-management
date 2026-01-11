<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Carbon\Month;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;
use function Illuminate\Support\weeks;

class EmployeeDashboardController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = $request->user()->id;
        $stats = [
            'total' => Booking::where('employee_id', $employeeId)->count(),
            'pending' => Booking::where('employee_id', $employeeId)->where('status', 'pending')->count(),
            'approved'  => Booking::where('employee_id', $employeeId)->where('status', 'approved')->count(),
            'completed' => Booking::where('employee_id', $employeeId)->where('status', 'completed')->count(),
            'today'     => Booking::where('employee_id', $employeeId)->whereDate('created_at', today())->count(),
            'this_week' => Booking::where('employee_id', $employeeId)->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'this_month' => Booking::where('employee_id', $employeeId)
                ->whereMonth('created_at', Carbon::now()->month)->count()
        ];
        $weeklyData = $this->getWeeklyChartData($employeeId);

        $monthlyData = $this->getMonthlyChartData($employeeId);

        $latestBookings = Booking::with(['user', 'property'])
            ->where('employee_id', $employeeId)
            ->latest()
            ->take(5)
            ->get();



        // dd([
        //     'weeklyData' => $weeklyData,
        //     'monthlyData' => $monthlyData,
        //     'stats' => $stats
        // ]);
        return view('dashboard.employee.home', compact(
            'stats',
            'latestBookings',
            'weeklyData',
            'monthlyData'
        ));
    }

    private function getWeeklyChartData($employeeId)
    {

        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $weeklyBookings = Booking::where('employee_id', $employeeId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('COUNT(*) as total')
            )->groupBy('day')->orderBy('day')->get()->keyBy('day');

        $labels = [];
        $data = [];
       for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);
        $dateString = $date->format('Y-m-d');
        
        $labels[] = $date->translatedFormat('D') . "\n" . $date->format('d M');
        $data[] = $weeklyBookings[$dateString]->total ?? 0;
    }
          return [
                'labels' => $labels,
                'data' => $data,
                'title' => 'Last 7 Days',
                'total' => array_sum($data)
            ];
    }

    private function getMonthlyChartData($employeeId)
    {
        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate = Carbon::now()->endOfMonth()->endOfDay();

        $monthlyBookings = Booking::where('employee_id', $employeeId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DAY(created_at) as day_number'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('day_number')
            ->orderBy('day_number')
            ->get()
            ->keyBy('day_number');

        $labels = [];
        $data = [];
        $daysInMonth = Carbon::now()->daysInMonth;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $labels[] = $day;
            $data[] = $monthlyBookings[$day]->total ?? 0;
        }
        return [
            'labels' => $labels,
            'data' => $data,
            'title' => 'This Month (' . Carbon::now()->format('F Y') . ')',
            'total' => array_sum($data)
        ];
    }
}
