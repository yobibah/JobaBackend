<?php

use App\Events\RatingEvent;
use App\Models\Rating;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PrestataireController;


Route::get('/', function () {
  $id=72;
    $rating = Rating::find($id);

    if (!$rating) {
        return "⚠️ Aucun rating trouvé avec ID= $id";
    }

    if(broadcast(new RatingEvent($rating))){
 return "Event envoyé ✅";
    };

   Route::post('/notifi', [PrestataireController::class, 'notifi']);
}); 
