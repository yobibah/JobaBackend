<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorairesTable extends Migration
{
    public function up()
    {
        Schema::create('horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_prestataires')->constrained('prestataires')->onDelete('cascade');
            $table->string('horaire_ouv');
            $table->string('jour_ouv');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('horaires');
    }
}
