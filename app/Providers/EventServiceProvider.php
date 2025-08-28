<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\RatingEvent;
use App\Listeners\SendRatingNotifications;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RatingEvent::class => [
            SendRatingNotifications::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
