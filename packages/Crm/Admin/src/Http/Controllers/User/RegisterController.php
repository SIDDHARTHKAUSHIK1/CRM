<?php

namespace Crm\Admin\Http\Controllers\User;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Crm\Admin\Http\Controllers\Controller;
use Crm\Admin\Http\Requests\RegisterRequest;
use Crm\Core\Services\TenantProvisioner;
use Crm\Core\TenantContext;

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     */
    public function create(): RedirectResponse|View
    {
        if (! config('crm.signup.enabled', true)) {
            session()->flash('warning', trans('admin::app.users.register.disabled'));

            return redirect()->route('admin.session.create');
        }

        if (auth()->guard('user')->check()) {
            return redirect()->route('admin.dashboard.index');
        }

        return view('admin::sessions.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request, TenantProvisioner $provisioner): RedirectResponse
    {
        if (! config('crm.signup.enabled', true)) {
            session()->flash('error', trans('admin::app.users.register.disabled'));

            return redirect()->route('admin.session.create');
        }

        // Honeypot check: bot prevention
        if ($request->filled('website')) {
            return redirect()->back()->withInput($request->except(['password', 'password_confirmation', 'website']));
        }

        $autoActivate = config('crm.signup.auto_activate', true);

        try {
            $result = $provisioner->provision(
                name: $request->input('company_name'),
                adminEmail: $request->input('email'),
                adminPassword: $request->input('password'),
                adminName: $request->input('name'),
                extra: [
                    'phone'         => $request->input('phone'),
                    'signup_ip'     => $request->ip(),
                    'signup_source' => 'web',
                    'user_status'   => $autoActivate ? 1 : 0,
                ]
            );

            $user = $result['user'];

            if ($autoActivate) {
                auth()->guard('user')->login($user);
                $request->session()->regenerate();
                TenantContext::setTenantId($user->tenant_id);
                $user->update(['last_login_at' => now()]);

                session()->flash('success', trans('admin::app.users.register.success'));

                return redirect()->route('admin.dashboard.index');
            }

            session()->flash('success', trans('admin::app.users.register.pending-activation'));

            return redirect()->route('admin.session.create');
        } catch (Exception $e) {
            Log::error('Tenant registration failed: ' . $e->getMessage(), [
                'email'   => $request->input('email'),
                'company' => $request->input('company_name'),
                'trace'   => $e->getTraceAsString(),
            ]);

            session()->flash('error', trans('admin::app.users.register.error'));

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation', 'website']));
        }
    }
}
