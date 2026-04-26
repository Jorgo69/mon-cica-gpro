<?php

namespace App\Listeners;

use App\Models\EmailSuppression;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

class CheckEmailSuppression
{
    /**
     * Intercepte les mails avant envoi et bloque ceux vers des adresses supprimees.
     */
    public function handle(MessageSending $event): bool
    {
        $recipients = $event->message->getTo();

        foreach ($recipients as $address) {
            $email = $address->getAddress();

            if (EmailSuppression::isSuppressed($email)) {
                Log::info("Email bloque par suppression list : {$email}");
                return false; // Annule l'envoi
            }
        }

        return true; // Autorise l'envoi
    }
}
