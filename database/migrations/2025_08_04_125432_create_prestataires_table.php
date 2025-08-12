<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestatairesTable extends Migration
{
    public function up()
    {
        Schema::create('prestataires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('adresse');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('profession');
            $table->string('numero')->unique();
            $table->string('profile')->default('default.png');
            $table->string('whatsapp');
            $table->boolean('boosted')->default(false);
            $table->string('token', 60);
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestataires');
    }
}
