<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreEmployeeRequest;

class AdminController extends Controller
{
    //
     //dashboard
    public function dashboard()
    {
        return response()->json([
            'message' => 'Welcome to Admin Dashboard'
        ]);
    }

    // add a new employee
    public function addEmployee(StoreEmployeeRequest $request)
{
    

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return response()->json([
        'message' => 'Employee created successfully',
        'user' => new UserResource($user)       
    ], 201);
}

}
