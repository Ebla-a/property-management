<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Services\AdminService;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Support\Facades\Log;

/**
 * AdminController
 *
 * Handles admin UI pages and delegates business operations to AdminService.
 */
class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
        $this->middleware(['auth:sanctum', 'check.active', 'role:admin']);
    }

    /**
     * Show a paginated list of users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the create employee form.
     */
    public function create()
    {
        return view('dashboard.users.create');
    }

    /**
     * Handle creation of a new employee.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        // Delegate creation and role assignment to service
        $this->adminService->addEmployee($data);

        return redirect()->route('dashboard.admin.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Show the role edit form for a user.
     */
    public function editRole(int $id)
    {
        $user = User::findOrFail($id);

        return view('dashboard.users.role', compact('user'));
    }

    /**
     * Apply role change for a user.
     */
    public function changeRole(Request $request, int $id)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,employee,customer'
        ]);

        $this->adminService->changeRole($id, $validated['role']);

        return redirect()->route('dashboard.admin.employees.index')
            ->with('success', 'User role updated successfully.');
    }

    /**
     * Show the account status edit page for a user.
     */
    public function editAccount(int $userId)
    {
        $user = User::findOrFail($userId);

        return view('dashboard.users.account', compact('user'));
    }

    /**
     * Toggle user activation state.
     */
    public function toggleUserStatus(Request $request, int $userId)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $this->adminService->toggleUserStatus($userId, (bool) $request->input('is_active'));

        return redirect()->route('dashboard.admin.employees.index')
            ->with('success', 'User account status updated successfully.');
    }

    /**
     * Change current admin password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $admin = $request->user();

        $this->adminService->changePassword($admin, $request->old_password, $request->new_password);

        return redirect()->route('dashboard.profile.edit')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Destroy (delete) a user.
     *
     * @param Request $request
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $userId)
    {
        try {
            $this->adminService->deleteUser($userId, $request->user());

            return redirect()->route('dashboard.admin.employees.index')
                ->with('success', 'User deleted successfully.');
        } catch (BadRequestHttpException $e) {
            // A purposeful check failed (e.g. trying to delete self or last admin)
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            // Unexpected error
            Log::error('Failed to delete user: ' . $e->getMessage(), [
                'userId' => $userId,
                'actorId' => $request->user()->id,
            ]);

            return redirect()->back()->with('error', 'An unexpected error occurred while deleting the user.');
        }
    }
}
