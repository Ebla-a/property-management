<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\ToggleStatusRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
            ->with('success', __('messages.user.create'));
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
    public function changeRole(UpdateRoleRequest $request, int $id)
    {
        $validated = $request->validated();

        $this->adminService->changeRole($id, $validated['role']);

        return redirect()->route('dashboard.admin.employees.index')
            ->with('success', __('messages.user.role_updated'));
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
    public function toggleUserStatus(ToggleStatusRequest $request, int $userId)
    {
        $validated = $request->validated();

        $this->adminService->toggleUserStatus($userId, (bool) $validated['is_active']);

        return redirect()->route('dashboard.admin.employees.index')
            ->with('success', __('messages.user.status_updated'));
    }

    /**
     * Change current admin password.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();
        $admin = $request->user();

        $this->adminService->changePassword($admin, $validated['old_password'], $validated['new_password']);

        return redirect()->route('dashboard.profile.edit')
            ->with('success', __('messages.user.password_changed'));
    }

    /**
     * Destroy (delete) a user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $userId)
    {
        try {
            $this->adminService->deleteUser($userId, $request->user());

            return redirect()->route('dashboard.admin.employees.index')
                ->with('success', __('messages.user.deleted'));
        } catch (BadRequestHttpException $e) {
            // A purposeful check failed (e.g. trying to delete self or last admin)
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            // Unexpected error
            Log::error('Failed to delete user: '.$e->getMessage(), [
                'userId' => $userId,
                'actorId' => $request->user()->id,
            ]);

            return redirect()->back()->with('error', __('messages.errors.unexpected_delete'));
        }
    }
}
