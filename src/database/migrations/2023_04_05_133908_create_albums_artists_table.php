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
        Schema::create('albums_artists', function (Blueprint $table) {
            $table->foreignId('artist_id');
            $table->foreign('artist_id')->references('id')->on('artists')->cascadeOnDelete();
            $table->foreignId('album_id');
            $table->foreign('album_id')->references('id')->on('albums')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums_artists');
    }
};
