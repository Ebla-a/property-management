<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB ;
use Svg\Tag\Rect;

class EmployeeDashboardController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = $request->user()->id;
        $stats = [
            'total' => Booking::where('employee_id' ,$employeeId)->count(),
            'pending' => Booking::where('employee_id' ,$employeeId)->where('status' ,'pending')->count(),
            'approved'  => Booking::where('employee_id', $employeeId)->where('status','approved')->count(),
            'completed' => Booking::where('employee_id', $employeeId)->where('status','completed')->count(),
            'today'     => Booking::where('employee_id', $employeeId)
                                ->whereDate('scheduled_at', today())
                                ->count(),
        ];
     $startDate = Carbon::today()->subDays(6);
    $endDate = Carbon::today();
    $weeklyBookings = Booking::where('employee_id', $employeeId)
        ->whereBetween('scheduled_at', [$startDate->startOfDay(), $endDate->endOfDay()])
        ->select(DB::raw('DATE(scheduled_at) as day'), DB::raw('count(*) as total'))
        ->groupBy('day')
        ->orderBy('day')
        ->get()
        ->keyBy('day');
        $weekLabels = [];
$weekData = [];
for ($i = 6; $i >= 0; $i--) {
    $date = Carbon::today()->subDays($i)->format('Y-m-d');
    $weekLabels[] = Carbon::parse($date)->format('D'); 
    $weekData[] = $weeklyBookings[$date]->total ?? 0; 
}
        
        $latestBookings = Booking::with(['user','property'])
            ->where('employee_id', $employeeId)
            ->latest()
            ->take(5)
            ->get();

         return view('dashboard.employee.home', compact('stats', 'latestBookings', 'weekLabels', 'weekData'));
 

    }
}
