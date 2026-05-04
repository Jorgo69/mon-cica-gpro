<?php

use App\Http\Controllers\IcalFeedController;

test('doit retourner un calendrier iCal avec un token valide', function () {
    $user = createOrgAdmin();
    $token = IcalFeedController::generateToken($user);

    $this->get("/ical/feed?token={$token}")
        ->assertStatus(200)
        ->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
});

test('doit contenir BEGIN:VCALENDAR dans le contenu', function () {
    $user = createOrgAdmin();
    $token = IcalFeedController::generateToken($user);

    $response = $this->get("/ical/feed?token={$token}");

    expect($response->getContent())->toContain('BEGIN:VCALENDAR');
    expect($response->getContent())->toContain('END:VCALENDAR');
});

test('doit retourner 403 quand le token est invalide', function () {
    $fakeToken = base64_encode('fake-user-id:fake-hmac');

    $this->get("/ical/feed?token={$fakeToken}")
        ->assertStatus(403);
});

test('doit retourner 403 quand aucun token n\'est fourni', function () {
    $this->get('/ical/feed')
        ->assertStatus(403);
});
