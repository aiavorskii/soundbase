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
        Schema::create('songs_album', function (Blueprint $table) {
            $table->foreignId('song_id');
            $table->foreign('song_id')->references('id')->on('songs')->cascadeOnDelete();
            $table->foreignId('album_id');
            $table->foreign('album_id')->references('id')->on('albums')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs_album');
    }
};
