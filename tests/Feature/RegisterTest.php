<?php

use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Config;
use Crm\Core\Models\Tenant;
use Crm\Lead\Models\Pipeline;
use Crm\User\Models\User;

beforeEach(function () {
    test()->withoutMiddleware(ThrottleRequests::class);
});

it('displays registration form at /admin/register', function () {
    test()->get(route('admin.register.create'))
        ->assertOK()
        ->assertSee('Create Your')
        ->assertSee('Account');
});

it('registers a new tenant workspace and auto logs in', function () {
    $email = 'sarah_' . uniqid() . '@acmerealty.com';
    $company = 'Acme Realty ' . rand(100, 999);

    $response = test()->post(route('admin.register.store'), [
        'name'                  => 'Sarah Connor',
        'company_name'          => $company,
        'email'                 => $email,
        'phone'                 => '+15551234567',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'agree'                 => '1',
    ]);

    $response->assertRedirect(route('admin.dashboard.index'));

    $user = User::withoutGlobalScopes()->where('email', $email)->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Sarah Connor');
    expect($user->phone)->toBe('+15551234567');

    $tenant = Tenant::withoutGlobalScopes()->find($user->tenant_id);
    expect($tenant)->not->toBeNull();
    expect($tenant->name)->toBe($company);
    expect($tenant->owner_user_id)->toBe($user->id);
    expect($tenant->trial_ends_at)->not->toBeNull();

    // Verify user is authenticated
    expect(auth()->guard('user')->id())->toBe($user->id);
});

it('validates required fields', function () {
    test()->post(route('admin.register.store'), [])
        ->assertSessionHasErrors(['name', 'company_name', 'email', 'password', 'agree']);
});

it('rejects duplicate email addresses across any tenant', function () {
    $existing = User::withoutGlobalScopes()->first();
    $email = $existing ? $existing->email : 'admin@example.com';

    test()->post(route('admin.register.store'), [
        'name'                  => 'Duplicate User',
        'company_name'          => 'Dup Corp',
        'email'                 => $email,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'agree'                 => '1',
    ])->assertSessionHasErrors(['email']);
});

it('enforces minimum 8 character password and confirmation match', function () {
    test()->post(route('admin.register.store'), [
        'name'                  => 'Short Pass',
        'company_name'          => 'Pass Corp',
        'email'                 => 'short_' . uniqid() . '@corp.com',
        'password'              => 'short',
        'password_confirmation' => 'mismatch',
        'agree'                 => '1',
    ])->assertSessionHasErrors(['password']);
});

it('requires acceptance of terms', function () {
    test()->post(route('admin.register.store'), [
        'name'                  => 'No Agree',
        'company_name'          => 'Terms Corp',
        'email'                 => 'terms_' . uniqid() . '@corp.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors(['agree']);
});

it('blocks honeypot submissions from bots', function () {
    $botEmail = 'bot_' . uniqid() . '@spam.com';

    $response = test()->post(route('admin.register.store'), [
        'name'                  => 'Bot Spammer',
        'company_name'          => 'Spam Inc',
        'email'                 => $botEmail,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'agree'                 => '1',
        'website'               => 'http://spamsite.com',
    ]);

    // Should not create user or tenant
    $botUser = User::withoutGlobalScopes()->where('email', $botEmail)->first();
    expect($botUser)->toBeNull();
});

it('provisions isolated pipeline and stages for new tenant', function () {
    $email = 'isolated_' . uniqid() . '@tenant.com';
    $company = 'Tenant Pipeline Test ' . rand(100, 999);

    test()->post(route('admin.register.store'), [
        'name'                  => 'Isolated Admin',
        'company_name'          => $company,
        'email'                 => $email,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'agree'                 => '1',
    ]);

    $user = User::withoutGlobalScopes()->where('email', $email)->first();
    expect($user)->not->toBeNull();

    // Verify tenant pipeline exists specifically for this tenant
    $pipelines = Pipeline::withoutGlobalScopes()->where('tenant_id', $user->tenant_id)->get();
    expect($pipelines->count())->toBeGreaterThan(0);
});

it('redirects to signin when signup is disabled', function () {
    Config::set('crm.signup.enabled', false);

    test()->get(route('admin.register.create'))
        ->assertRedirect(route('admin.session.create'));

    test()->post(route('admin.register.store'), [
        'name'                  => 'Disabled Signup',
        'company_name'          => 'Disabled Corp',
        'email'                 => 'disabled_' . uniqid() . '@corp.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
        'agree'                 => '1',
    ])->assertRedirect(route('admin.session.create'));
});
