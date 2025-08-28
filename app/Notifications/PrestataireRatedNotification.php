<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PrestataireRatedNotification extends Notification
{
    use Queueable;

    protected $rating;

    public function __construct($rating)
    {
        $this->rating = $rating;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'prestataire_id' => $this->rating->prestataires_id,
            'user_id'        => $this->rating->users_id,
            'note'           => $this->rating->notes,
            'message'        => "Vous avez reçu une note de {$this->rating->notes}/5",
        ];
    }
}
