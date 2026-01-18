<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get notifications sorted by newest first
        $notifications = $user->notifications()->latest();

        // Employees should only see booking notifications
        if ($user->hasRole('employee')) {
            $notifications = $notifications->where('data->type', 'booking');
        }

        // Paginate if needed
        $notifications = $notifications->paginate(20);

        return view('dashboard.notifications.index', compact('notifications'));
    }
}
