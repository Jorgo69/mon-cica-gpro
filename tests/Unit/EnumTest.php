<?php

use App\Enums\AccountType;
use App\Enums\ActivityStatus;
use App\Enums\AdminCategoryType;
use App\Enums\Currency;
use App\Enums\InvitationStatus;
use App\Enums\LogframeDisplayFormat;
use App\Enums\ProjectStatus;

/*
|--------------------------------------------------------------------------
| ActivityStatus (8 cases) — label, color, icon
|--------------------------------------------------------------------------
*/

test('ActivityStatus: chaque case possede un label non vide', function (ActivityStatus $status) {
    expect($status->label())->toBeString()->not->toBeEmpty();
})->with(ActivityStatus::cases());

test('ActivityStatus: chaque case possede une color non vide', function (ActivityStatus $status) {
    expect($status->color())->toBeString()->not->toBeEmpty();
})->with(ActivityStatus::cases());

test('ActivityStatus: chaque case possede un icon non vide', function (ActivityStatus $status) {
    expect($status->icon())->toBeString()->not->toBeEmpty();
})->with(ActivityStatus::cases());

test('ActivityStatus: doit avoir exactement 8 cases', function () {
    expect(ActivityStatus::cases())->toHaveCount(8);
});

/*
|--------------------------------------------------------------------------
| ProjectStatus (6 cases) — label, color
|--------------------------------------------------------------------------
*/

test('ProjectStatus: chaque case possede un label non vide', function (ProjectStatus $status) {
    expect($status->label())->toBeString()->not->toBeEmpty();
})->with(ProjectStatus::cases());

test('ProjectStatus: chaque case possede une color non vide', function (ProjectStatus $status) {
    expect($status->color())->toBeString()->not->toBeEmpty();
})->with(ProjectStatus::cases());

test('ProjectStatus: doit avoir exactement 10 cases', function () {
    expect(ProjectStatus::cases())->toHaveCount(10);
});

/*
|--------------------------------------------------------------------------
| AccountType (4 cases) — label, color, icon
|--------------------------------------------------------------------------
*/

test('AccountType: chaque case possede un label non vide', function (AccountType $type) {
    expect($type->label())->toBeString()->not->toBeEmpty();
})->with(AccountType::cases());

test('AccountType: chaque case possede une color non vide', function (AccountType $type) {
    expect($type->color())->toBeString()->not->toBeEmpty();
})->with(AccountType::cases());

test('AccountType: chaque case possede un icon non vide', function (AccountType $type) {
    expect($type->icon())->toBeString()->not->toBeEmpty();
})->with(AccountType::cases());

test('AccountType: doit avoir exactement 4 cases', function () {
    expect(AccountType::cases())->toHaveCount(4);
});

/*
|--------------------------------------------------------------------------
| InvitationStatus (4 cases) — label, color, icon
|--------------------------------------------------------------------------
*/

test('InvitationStatus: chaque case possede un label non vide', function (InvitationStatus $status) {
    expect($status->label())->toBeString()->not->toBeEmpty();
})->with(InvitationStatus::cases());

test('InvitationStatus: chaque case possede une color non vide', function (InvitationStatus $status) {
    expect($status->color())->toBeString()->not->toBeEmpty();
})->with(InvitationStatus::cases());

test('InvitationStatus: chaque case possede un icon non vide', function (InvitationStatus $status) {
    expect($status->icon())->toBeString()->not->toBeEmpty();
})->with(InvitationStatus::cases());

test('InvitationStatus: doit avoir exactement 4 cases', function () {
    expect(InvitationStatus::cases())->toHaveCount(4);
});

/*
|--------------------------------------------------------------------------
| AdminCategoryType (6 cases) — label, color, icon
|--------------------------------------------------------------------------
*/

test('AdminCategoryType: chaque case possede un label non vide', function (AdminCategoryType $type) {
    expect($type->label())->toBeString()->not->toBeEmpty();
})->with(AdminCategoryType::cases());

test('AdminCategoryType: chaque case possede une color non vide', function (AdminCategoryType $type) {
    expect($type->color())->toBeString()->not->toBeEmpty();
})->with(AdminCategoryType::cases());

test('AdminCategoryType: chaque case possede un icon non vide', function (AdminCategoryType $type) {
    expect($type->icon())->toBeString()->not->toBeEmpty();
})->with(AdminCategoryType::cases());

test('AdminCategoryType: doit avoir exactement 6 cases', function () {
    expect(AdminCategoryType::cases())->toHaveCount(6);
});

/*
|--------------------------------------------------------------------------
| Currency (8 cases) — label, symbol, decimals
|--------------------------------------------------------------------------
*/

test('Currency: chaque case possede un label non vide', function (Currency $currency) {
    expect($currency->label())->toBeString()->not->toBeEmpty();
})->with(Currency::cases());

test('Currency: chaque case possede un symbol non vide', function (Currency $currency) {
    expect($currency->symbol())->toBeString()->not->toBeEmpty();
})->with(Currency::cases());

test('Currency: chaque case possede un nombre de decimales valide', function (Currency $currency) {
    expect($currency->decimals())->toBeInt()->toBeGreaterThanOrEqual(0);
})->with(Currency::cases());

test('Currency: XOF et XAF ont 0 decimales', function () {
    expect(Currency::XOF->decimals())->toBe(0);
    expect(Currency::XAF->decimals())->toBe(0);
});

test('Currency: EUR, USD, GBP ont 2 decimales', function () {
    expect(Currency::EUR->decimals())->toBe(2);
    expect(Currency::USD->decimals())->toBe(2);
    expect(Currency::GBP->decimals())->toBe(2);
});

test('Currency: doit avoir exactement 8 cases', function () {
    expect(Currency::cases())->toHaveCount(8);
});

/*
|--------------------------------------------------------------------------
| LogframeDisplayFormat (3 cases) — label, icon, color
|--------------------------------------------------------------------------
*/

test('LogframeDisplayFormat: chaque case possede un label non vide', function (LogframeDisplayFormat $format) {
    expect($format->label())->toBeString()->not->toBeEmpty();
})->with(LogframeDisplayFormat::cases());

test('LogframeDisplayFormat: chaque case possede un icon non vide', function (LogframeDisplayFormat $format) {
    expect($format->icon())->toBeString()->not->toBeEmpty();
})->with(LogframeDisplayFormat::cases());

test('LogframeDisplayFormat: chaque case possede une color non vide', function (LogframeDisplayFormat $format) {
    expect($format->color())->toBeString()->not->toBeEmpty();
})->with(LogframeDisplayFormat::cases());

test('LogframeDisplayFormat: doit avoir exactement 3 cases', function () {
    expect(LogframeDisplayFormat::cases())->toHaveCount(3);
});

/*
|--------------------------------------------------------------------------
| Locale switching — labels change with locale
|--------------------------------------------------------------------------
*/

test('les labels changent quand on passe en locale anglaise', function () {
    app()->setLocale('en');

    expect(ActivityStatus::DRAFT->label())->toBe('Draft');
    expect(ProjectStatus::ACTIVE->label())->toBe('Active');
    expect(AccountType::ROOT->label())->toBe('Super Administrator');
    expect(InvitationStatus::PENDING->label())->toBe('Pending');
    expect(LogframeDisplayFormat::TABLE->label())->toBe('Table');
    expect(Currency::EUR->label())->toBe('Euro');
});

test('les labels sont en francais quand la locale est fr', function () {
    app()->setLocale('fr');

    expect(ActivityStatus::DRAFT->label())->toBe('Brouillon');
    expect(ProjectStatus::ACTIVE->label())->toBe('En cours');
    expect(AccountType::ROOT->label())->toBe('Super Administrateur');
    expect(InvitationStatus::PENDING->label())->toBe('En attente');
    expect(LogframeDisplayFormat::TABLE->label())->toBe('Tableau');
    expect(Currency::EUR->label())->toBe('Euro');
});
