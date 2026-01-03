<?php


namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertiesReportController extends Controller
{
    public function index()
    {
        return view('dashboard.reports.properties');

    }

}
