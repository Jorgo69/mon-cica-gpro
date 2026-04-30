<?php

namespace App\Http\Controllers;

use App\Models\EmailSuppression;

class EmailUnsubscribeController extends Controller
{
    public function unsubscribe(string $token)
    {
        $email = $this->decodeToken($token);

        if (!$email) {
            abort(404, 'Lien invalide.');
        }

        EmailSuppression::suppress($email, 'unsubscribed', 'Desabonnement volontaire');

        return view('emails.unsubscribed', ['email' => $email]);
    }

    public function resubscribe(string $token)
    {
        $email = $this->decodeToken($token);

        if (!$email) {
            abort(404, 'Lien invalide.');
        }

        EmailSuppression::unsuppress($email);

        return view('emails.resubscribed', ['email' => $email]);
    }

    /**
     * Genere un token signe pour un email (utilisable dans les liens de mail).
     */
    public static function generateToken(string $email): string
    {
        return base64_encode($email . '|' . hash_hmac('sha256', $email, config('app.key')));
    }

    private function decodeToken(string $token): ?string
    {
        $decoded = base64_decode($token);
        if (!$decoded || !str_contains($decoded, '|')) {
            return null;
        }

        [$email, $hash] = explode('|', $decoded, 2);

        if (!hash_equals(hash_hmac('sha256', $email, config('app.key')), $hash)) {
            return null;
        }

        return $email;
    }
}
