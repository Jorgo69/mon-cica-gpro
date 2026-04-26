<?php

namespace App\Listeners;

use App\Models\EmailSuppression;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class HandleFailedEmail
{
    /**
     * Apres envoi, on pourrait detecter les bounces via les headers de reponse.
     * Pour Gmail SMTP, les bounces arrivent en retour de mail.
     * Ce listener log les envois pour traçabilite.
     */
    public function handle(MessageSent $event): void
    {
        $recipients = $event->message->getTo();
        foreach ($recipients as $address) {
            Log::channel('mail')->info("Email envoye a : {$address->getAddress()}");
        }
    }
}
