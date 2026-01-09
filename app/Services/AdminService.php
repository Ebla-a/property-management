<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * AdminService
 *
 * Encapsulates business logic related to admin user management.
 */
class AdminService
{
    /**
     * Create a new employee user and assign a role.
     *
     * @param array $data
     * @return User
     */
    public function addEmployee(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Create the user
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Assign role (default to 'employee' if not provided)
            $role = $data['role'] ?? 'employee';
            if (! in_array($role, ['admin', 'employee', 'customer'])) {
                // If the provided role is invalid, rollback by throwing.
                throw new BadRequestHttpException('Invalid role provided.');
            }

            $user->assignRole($role);

            return $user;
        });
    }

    /**
     * Change a user's role using Spatie's role methods.
     * This will remove previous roles and set the provided one.
     *
     * @param int $userId
     * @param string $role
     * @return User
     */
    public function changeRole(int $userId, string $role): User
    {
        return DB::transaction(function () use ($userId, $role) {
            if (! in_array($role, ['admin', 'employee', 'customer'])) {
                throw new BadRequestHttpException('Invalid role');
            }

            $user = User::findOrFail($userId);

            // Sync roles to ensure the user only has the new role
            $user->syncRoles([$role]);

            return $user;
        });
    }

    /**
     * Toggle user active status.
     *
     * @param int $userId
     * @param bool $status
     * @return User
     */
    public function toggleUserStatus(int $userId, bool $status): User
    {
        return DB::transaction(function () use ($userId, $status) {
            $user = User::findOrFail($userId);

            $user->update(['is_active' => $status]);

            return $user;
        });
    }

    /**
     * Change the password for the given admin user after verifying old password.
     * Throws BadRequestHttpException if old password doesn't match.
     *
     * @param User $admin
     * @param string $oldPassword
     * @param string $newPassword
     * @return void
     */
    public function changePassword(User $admin, string $oldPassword, string $newPassword): void
    {
        DB::transaction(function () use ($admin, $oldPassword, $newPassword) {
            if (! Hash::check($oldPassword, $admin->password)) {
                throw new BadRequestHttpException('Old password is incorrect.');
            }

            $admin->password = Hash::make($newPassword);
            $admin->save();
        });
    }

    /**
     * Delete a user with safety checks:
     * - Prevent deleting yourself.
     * - Prevent deleting the last admin.
     *
     * @param int $userId  The id of the user to delete
     * @param User $requester The user performing the delete (for safety checks)
     * @return void
     *
     * @throws BadRequestHttpException on invalid action
     */
    public function deleteUser(int $userId, User $requester): void
    {
        DB::transaction(function () use ($userId, $requester) {
            // Prevent deleting yourself
            if ($requester->id === $userId) {
                throw new BadRequestHttpException('You cannot delete your own account.');
            }

            $user = User::findOrFail($userId);

            // If the target user is an admin, ensure there is at least one other admin left.
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                // Using Spatie's scope to count users with 'admin' role
                $adminCount = User::role('admin')->count();

                // If there is only one admin (this user), do not allow deletion
                if ($adminCount <= 1) {
                    throw new BadRequestHttpException('Cannot delete the last admin account.');
                }
            }

            // Optionally: detach roles/permissions (not required if cascade is set),
            // but safe to remove roles before deletion.
            if (method_exists($user, 'syncRoles')) {
                $user->syncRoles([]);
            }

            // Delete the user
            $user->delete();
        });
    }
}
