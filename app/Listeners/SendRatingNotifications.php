<?php

namespace App\Listeners;

use App\Events\RatingEvent;
use App\Models\User;
use App\Notifications\PrestataireRatedNotification;

class SendRatingNotifications
{
    /**
     * Handle the event.
     */
    public function handle(RatingEvent $event): void
    {
        $prestataire = User::find($event->rating->prestataires_id);

        if ($prestataire) {
            $prestataire->notify(new PrestataireRatedNotification($event->rating));
          
        }
    }
}
