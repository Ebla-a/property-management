<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
        $this->middleware(['auth:sanctum', 'check.active', 'role:admin']);
    }

    // create an employee 
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        $employee = $this->adminService->addEmployee($data);

        return view('dashboard.users.create');
    }

    // Role change 
    public function changeRole(Request $request, int $id)
    {
        $validated = $request->validate([  'role' => 'required|in:admin,employee,customer']);

        $user = $this->adminService->changeRole($id, $validated['role']);

        return view('dashboard.users.role');
    }

    // Deactivate and activate account
     public function toggleUserStatus(Request $request, int $userId)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $user = $this->adminService->toggleUserStatus(
            $userId,
            $request->is_active
        );

        return response()->json([
            'message' => $request->is_active
                ? 'User account activated successfully'
                : 'User account disabled successfully',
            'user' => $user
        ]);
    }

    // Change the password

     public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $admin = $request->user(); 

        $this->adminService->changePassword( $admin,$request->old_password,$request->new_password);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
