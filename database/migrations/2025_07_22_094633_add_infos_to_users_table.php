<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
          
            $table->string('nom');
            $table->string('prenom');
            $table->string('typeCompte');
            $table->string('adresse');
            $table->decimal('longitude',10,8);
            $table->decimal('latitude',11,8);
            $table->string('autre');
            $table->string('numero');
            $table->string('profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
