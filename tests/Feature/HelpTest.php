it('shows the help page to an authenticated admin', function () {
    $admin = getDefaultAdmin();

    $this->actingAs($admin, 'user')
        ->get(route('admin.help.index'))
        ->assertOk();
});
