<?php

use App\Models\User;
use App\Models\Organization;
use App\Enums\OrganizationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create an organization', function () {
    $organization = Organization::create([
        'name' => 'Test Corp',
        'slug' => 'test-corp',
        'status' => OrganizationStatus::ACTIVE,
    ]);

    expect($organization->name)->toBe('Test Corp');
    expect($organization->slug)->toBe('test-corp');
    expect($organization->status)->toBe(OrganizationStatus::ACTIVE);
    expect($organization->id)->toBeUuid();
});

test('can link a user to an organization', function () {
    $organization = Organization::create([
        'name' => 'Test Corp',
        'slug' => 'test-corp',
    ]);

    $user = User::factory()->create([
        'organization_id' => $organization->id,
    ]);

    expect($user->organization->id)->toBe($organization->id);
    expect($organization->users)->toHaveCount(1);
});

test('organization has default status as trial', function () {
    $organization = Organization::create([
        'name' => 'New Corp',
        'slug' => 'new-corp',
    ]);

    expect($organization->status)->toBe(OrganizationStatus::TRIAL);
});
