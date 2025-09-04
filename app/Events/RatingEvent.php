<?php
namespace App\Events;

use App\Models\Rating;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Log;

class RatingEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
        \Log::info("🚀 RatingEvent créé avec ID={$rating->id}");
    }

    public function broadcastOn()
    {
        // ✅ Canal privé pour le prestataire concerné
        return new PrivateChannel('prestataire.' . $this->rating->prestataires_id);
    }

    public function broadcastWith(): array
    {
        Log::info('message de broadcast : ',[
   [
            'prestataire_id' => $this->rating->prestataires_id,
            'user_id'        => $this->rating->users_id,
            'note'           => $this->rating->notes,
            'message'        => "Vous avez reçu une note de {$this->rating->notes}/5"
        ]
        ]);
        return [
            'prestataire_id' => $this->rating->prestataires_id,
            'user_id'        => $this->rating->users_id,
            'note'           => $this->rating->notes,
            'message'        => "Vous avez reçu une note de {$this->rating->notes}/5"
        ];
    }

    public function broadcastAs(): string
    {
        return 'rating.received'; 
    }
}
