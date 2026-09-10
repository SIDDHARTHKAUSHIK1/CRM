<?php

namespace Crm\Admin\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Crm\Activity\Models\Activity;
use Crm\Admin\DataGrids\Admin\EmployeeDataGrid;
use Crm\Admin\Http\Controllers\Controller;
use Crm\Admin\Notifications\User\Create as UserCreatedNotification;
use Crm\Lead\Models\Lead;
use Crm\Quote\Models\Quote;
use Crm\User\Models\User;
use Crm\User\Repositories\GroupRepository;
use Crm\User\Repositories\RoleRepository;
use Crm\User\Repositories\UserRepository;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository,
        protected GroupRepository $groupRepository
    ) {}

    /**
     * Ensure current user is an admin (permission_type === 'all').
     */
    protected function ensureIsAdmin(): void
    {
        $role = auth()->guard('user')->user()?->role;

        if (! $role || $role->permission_type !== 'all') {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Display a listing of employees.
     */
    public function index(): View|JsonResponse
    {
        $this->ensureIsAdmin();

        if (request()->ajax()) {
            return datagrid(EmployeeDataGrid::class)->process();
        }

        $roles = $this->roleRepository->all();
        $groups = $this->groupRepository->all();

        $totalEmployees = $this->userRepository->count();
        $activeEmployees = $this->userRepository->findWhere(['status' => 1])->count();
        $inactiveEmployees = $this->userRepository->findWhere(['status' => 0])->count();

        return view('admin::admin-panel.employees.index', compact(
            'roles',
            'groups',
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees'
        ));
    }

    /**
     * Return roles and groups for creating an employee.
     */
    public function create(): JsonResponse
    {
        $this->ensureIsAdmin();

        $roles = $this->roleRepository->all();
        $groups = $this->groupRepository->all();

        return new JsonResponse([
            'roles'  => $roles,
            'groups' => $groups,
        ]);
    }

    /**
     * Store a newly created employee account.
     */
    public function store(): JsonResponse
    {
        // Explicit hardcoded admin check
        if (! auth()->guard('user')->user()?->role || auth()->guard('user')->user()->role->permission_type !== 'all') {
            abort(403, 'Unauthorized access.');
        }

        $this->validate(request(), [
            'email'            => 'required|email|unique:users,email',
            'name'             => 'required',
            'password'         => 'nullable',
            'confirm_password' => 'nullable|required_with:password|same:password',
            'role_id'          => 'required|integer|exists:roles,id',
            'status'           => 'nullable|boolean|in:0,1',
            'view_permission'  => 'nullable|string|in:global,group,individual',
            'groups'           => 'required_if:view_permission,group|array',
            'groups.*'         => 'integer|exists:groups,id',
        ]);

        $data = request()->all();
        $data['view_permission'] = $data['view_permission'] ?: 'individual';

        if (isset($data['password']) && $data['password']) {
            $data['password_plain'] = Crypt::encryptString($data['password']);
            $data['password'] = bcrypt($data['password']);
        }

        Event::dispatch('settings.user.create.before');

        $employee = $this->userRepository->create($data);

        $employee->groups()->sync($data['groups'] ?? []);

        try {
            Mail::queue(new UserCreatedNotification($employee));
        } catch (\Exception $e) {
            report($e);
        }

        Event::dispatch('settings.user.create.after', $employee);
        Event::dispatch('user.admin.employee.created', [
            'admin_id'    => auth()->guard('user')->id(),
            'employee_id' => $employee->id,
            'role_id'     => $employee->role_id,
        ]);

        Log::info('Admin created employee account', [
            'admin_id'    => auth()->guard('user')->id(),
            'employee_id' => $employee->id,
            'role_id'     => $employee->role_id,
        ]);

        return new JsonResponse([
            'data'    => $employee,
            'message' => trans('admin::app.admin-panel.employees.create-success'),
        ]);
    }

    /**
     * Detailed employee view with work summary and recent activity.
     */
    public function view(int $id): View
    {
        $this->ensureIsAdmin();

        $employee = $this->userRepository->with(['role', 'groups'])->findOrFail($id);

        $leadsCount = Lead::where('user_id', $id)->count();
        $quotesCount = Quote::where('user_id', $id)->count();
        $activitiesCount = Activity::where('user_id', $id)->count();

        $recentLeads = Lead::where('user_id', $id)->latest()->take(10)->get();
        $recentQuotes = Quote::where('user_id', $id)->latest()->take(10)->get();
        $recentActivities = Activity::where('user_id', $id)->latest()->take(10)->get();

        $roles = $this->roleRepository->all();
        $groups = $this->groupRepository->all();

        return view('admin::admin-panel.employees.view', compact(
            'employee',
            'leadsCount',
            'quotesCount',
            'activitiesCount',
            'recentLeads',
            'recentQuotes',
            'recentActivities',
            'roles',
            'groups'
        ));
    }

    /**
     * Fetch employee record for editing.
     */
    public function edit(int $id): JsonResponse
    {
        $this->ensureIsAdmin();

        $employee = $this->userRepository->with(['role', 'groups'])->findOrFail($id);

        return new JsonResponse([
            'data' => $employee,
        ]);
    }

    /**
     * Update employee details (name, email, role, status, view_permission).
     */
    public function update(int $id): JsonResponse
    {
        $this->ensureIsAdmin();

        $this->validate(request(), [
            'email'           => 'required|email|unique:users,email,'.$id,
            'name'            => 'required|string',
            'role_id'         => 'required|integer|exists:roles,id',
            'status'          => 'nullable|boolean|in:0,1',
            'view_permission' => 'required|string|in:global,group,individual',
            'groups'          => 'required_if:view_permission,group|array',
            'groups.*'        => 'integer|exists:groups,id',
        ]);

        $data = request()->only([
            'name',
            'email',
            'role_id',
            'status',
            'view_permission',
        ]);

        $data['status'] = request()->boolean('status');

        Event::dispatch('settings.user.update.before', $id);

        $employee = $this->userRepository->update($data, $id);
        $employee->groups()->sync(request('groups', []));

        Event::dispatch('settings.user.update.after', $employee);

        return new JsonResponse([
            'data'    => $employee,
            'message' => trans('admin::app.admin-panel.employees.edit.update-success'),
        ]);
    }

    /**
     * Reset / set a new password for employee.
     */
    public function updatePassword(int $id): JsonResponse
    {
        $this->ensureIsAdmin();

        $this->validate(request(), [
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        $plain = request('password');

        $data = [
            'password'       => bcrypt($plain),
            'password_plain' => Crypt::encryptString($plain),
        ];

        Event::dispatch('settings.user.update.before', $id);

        $employee = $this->userRepository->update($data, $id);

        Event::dispatch('settings.user.update.after', $employee);

        return new JsonResponse([
            'message' => trans('admin::app.admin-panel.employees.password.reset-success'),
        ]);
    }

    /**
     * Reveal employee's stored plaintext password.
     */
    public function revealPassword(Request $request, int $id): JsonResponse
    {
        $this->ensureIsAdmin();

        $request->validate([
            'admin_password' => 'required|string',
        ]);

        $admin = auth()->guard('user')->user();

        if (! Hash::check($request->input('admin_password'), $admin->password)) {
            return new JsonResponse([
                'message' => trans('admin::app.admin-panel.employees.password.invalid-admin-password'),
            ], 422);
        }

        $employee = $this->userRepository->findOrFail($id);

        if (empty($employee->password_plain)) {
            return new JsonResponse([
                'message' => trans('admin::app.admin-panel.employees.password.no-plain-password'),
            ], 404);
        }

        try {
            $plain = Crypt::decryptString($employee->password_plain);
        } catch (\Exception $e) {
            report($e);

            return new JsonResponse([
                'message' => trans('admin::app.admin-panel.employees.password.no-plain-password'),
            ], 500);
        }

        Event::dispatch('user.admin.password.revealed', [$admin->id, $employee->id]);
        Log::info("Admin #{$admin->id} ({$admin->email}) revealed password of employee #{$employee->id} ({$employee->email}) at " . now()->toIso8601String());

        return new JsonResponse([
            'success'  => true,
            'password' => $plain,
        ]);
    }

    /**
     * Switch account / Login as target employee.
     */
    public function impersonate(int $id): RedirectResponse
    {
        $this->ensureIsAdmin();

        $employee = $this->userRepository->with('role')->findOrFail($id);

        if ($employee->role && $employee->role->permission_type === 'all') {
            session()->flash('error', trans('admin::app.admin-panel.impersonate.cannot_impersonate_admin'));

            return redirect()->back();
        }

        $admin = auth()->guard('user')->user();

        session(['impersonator_id' => $admin->id]);

        auth()->guard('user')->loginUsingId($employee->id);

        Event::dispatch('user.admin.impersonate.start', [$admin->id, $employee->id]);
        Log::info("Admin #{$admin->id} ({$admin->email}) started impersonating employee #{$employee->id} ({$employee->email}) at " . now()->toIso8601String());

        session()->flash('success', trans('admin::app.admin-panel.impersonate.success', ['name' => $employee->name]));

        if (! $employee->hasPermission('dashboard')) {
            $allowedMenu = menu()->getItems('admin');
            if ($allowedMenu->isNotEmpty()) {
                return redirect($allowedMenu->first()->getUrl());
            }
        }

        return redirect()->route('admin.dashboard.index');
    }

    /**
     * Stop impersonating and return to administrator account.
     */
    public function impersonateStop(): RedirectResponse
    {
        if (! session()->has('impersonator_id')) {
            return redirect()->route('admin.dashboard.index');
        }

        $impersonatorId = session('impersonator_id');
        $employee = auth()->guard('user')->user();

        session()->forget('impersonator_id');

        auth()->guard('user')->loginUsingId($impersonatorId);
        $admin = auth()->guard('user')->user();

        Event::dispatch('user.admin.impersonate.stop', [$admin->id, $employee?->id]);
        Log::info("Admin #{$admin->id} ({$admin->email}) stopped impersonating employee #{$employee?->id} at " . now()->toIso8601String());

        session()->flash('success', trans('admin::app.admin-panel.impersonate.stop_success'));

        return redirect()->route('admin.panel.employees.index');
    }

    /**
     * Delete employee account with safeguards.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->ensureIsAdmin();

        if (auth()->guard('user')->id() == $id) {
            return new JsonResponse([
                'message' => trans('admin::app.admin-panel.employees.delete.cannot-delete-self'),
            ], 400);
        }

        if ($this->userRepository->count() <= 1) {
            return new JsonResponse([
                'message' => trans('admin::app.admin-panel.employees.delete.cannot-delete-last-user'),
            ], 400);
        }

        $target = $this->userRepository->with('role')->findOrFail($id);

        if ($target->role && $target->role->permission_type === 'all') {
            $adminCount = User::whereHas('role', fn ($q) => $q->where('permission_type', 'all'))->count();

            if ($adminCount <= 1) {
                return new JsonResponse([
                    'message' => trans('admin::app.admin-panel.employees.delete.cannot-delete-last-admin'),
                ], 400);
            }
        }

        try {
            Event::dispatch('user.admin.delete.before', $id);

            $this->userRepository->delete($id);

            Event::dispatch('user.admin.delete.after', $id);

            return new JsonResponse([
                'message' => trans('admin::app.admin-panel.employees.delete.success'),
            ], 200);
        } catch (\Exception $e) {
            report($e);

            return new JsonResponse([
                'message' => trans('admin::app.admin-panel.employees.delete.success'),
            ], 500);
        }
    }
}
