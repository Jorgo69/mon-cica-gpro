<?php

use App\Enums\AccountType;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class)->in('Unit');
uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function seedPermissions(): void
{
    (new \Database\Seeders\PermissionSeeder())->run();
}

function createOrg(array $attrs = []): Organization
{
    return Organization::factory()->create($attrs);
}

function createUser(array $attrs = [], ?Organization $org = null): User
{
    if ($org) {
        $attrs['organization_id'] = $org->id;
    }

    return User::factory()->create($attrs);
}

function createOrgAdmin(?Organization $org = null): User
{
    $org ??= createOrg();
    return createUser(['role' => AccountType::ORG_ADMIN], $org);
}

function createOrgUser(?Organization $org = null): User
{
    $org ??= createOrg();
    return createUser(['role' => AccountType::ORG_USER], $org);
}

function createRoot(): User
{
    return createUser([
        'role' => AccountType::ROOT,
        'organization_id' => null,
    ]);
}

function createIndependent(): User
{
    return createUser([
        'role' => AccountType::INDEPENDENT,
        'organization_id' => null,
    ]);
}

function loginAs(User $user): User
{
    test()->actingAs($user);
    return $user;
}
