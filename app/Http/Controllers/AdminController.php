<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    //
     // صفحة dashboard
    public function dashboard()
    {
        return response()->json([
            'message' => 'Welcome to Admin Dashboard'
        ]);
    }

    // إضافة موظف جديد
    public function addEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $employeeRole = Role::where('name', 'employee')->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $employeeRole->id,
        ]);

        return response()->json([
            'message' => 'Employee created successfully',
            'user' => $user
        ], 201);
    }
}
