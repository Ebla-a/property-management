<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PropertiesReportController extends Controller
{
    public function index()
    {
        return view('dashboard.reports.properties');

    }
    
}
