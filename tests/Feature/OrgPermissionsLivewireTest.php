<?php

use App\Livewire\V1\Admin\OrgPermissionsLivewire;
use Livewire\Livewire;

test('org permissions page renders for org admin', function () {
    seedPermissions();

    $org = createOrg();
    $admin = createOrgAdmin($org);

    setPermissionsTeamId(null);
    $admin->assignRole('ORG_ADMIN');
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    loginAs($admin);

    Livewire::test(OrgPermissionsLivewire::class)
        ->assertStatus(200);
});

test('org permissions page displays organization members', function () {
    seedPermissions();

    $org = createOrg();
    $admin = createOrgAdmin($org);
    $member = createOrgUser($org);

    setPermissionsTeamId(null);
    $admin->assignRole('ORG_ADMIN');
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    loginAs($admin);

    Livewire::test(OrgPermissionsLivewire::class)
        ->assertSee($admin->name)
        ->assertSee($member->name);
});

test('org permissions search filters members by name', function () {
    seedPermissions();

    $org = createOrg();
    $admin = createOrgAdmin($org);
    $alice = createUser(['name' => 'Alice Dupont', 'role' => \App\Enums\AccountType::ORG_USER], $org);
    $bob = createUser(['name' => 'Bob Martin', 'role' => \App\Enums\AccountType::ORG_USER], $org);

    setPermissionsTeamId(null);
    $admin->assignRole('ORG_ADMIN');
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

    loginAs($admin);

    Livewire::test(OrgPermissionsLivewire::class)
        ->set('search', 'Alice')
        ->assertSee('Alice Dupont')
        ->assertDontSee('Bob Martin');
});
