<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
class AdminService
{
    public function addEmployee(array $data): User
    {
        return DB::transaction(function () use ($data) {

            return User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        });
    }

    public function changeRole(int $userId, string $role): User
    {
        return DB::transaction(function () use ($userId, $role) {

        if (!in_array($role, ['admin', 'employee', 'customer'])) {
            throw new BadRequestHttpException('Invalid role');
        }

        $user = User::findOrFail($userId);

        $user->syncRoles($role);

        return $user;
    });
    }
    public function toggleUserStatus(int $userId, bool $status): User
    {

        return DB::transaction(function () use ($userId, $status) {

        $user = User::findOrFail($userId);

        $user->update(['is_active' => $status]);
   
        return $user;
    });
    }
      public function changePassword(User $admin, string $oldPassword, string $newPassword)
    {
        return DB::transaction(function () use ($admin, $oldPassword, $newPassword) {

        if (!Hash::check($oldPassword, $admin->password)) 
        {
             throw new BadRequestHttpException('Old password is incorrect.');
        }
        $admin->password = Hash::make($newPassword);
        $admin->save();
    });
    }

    public function propertiesReport(array $filters): array
    {
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

    return [
        'total_properties' => $query->count(),

        'by_status' => [
            'available' => Property::where('status', 'available')->count(),
            'booked'    => Property::where('status', 'booked')->count(),
            'rented'    => Property::where('status', 'rented')->count(),
            'hidden'    => Property::where('status', 'hidden')->count(),
        ],

        'properties' => $query->latest()->get(),
    ];
    }
}
