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
        Schema::create('comic_genres', function (Blueprint $table) {
            // Foreign key to comics
            $table->unsignedBigInteger('comic_id');
            $table->foreign('comic_id')
                  ->references('comic_id')
                  ->on('comics')
                  ->onDelete('cascade');
            
            // Foreign key to genres
            $table->unsignedBigInteger('genre_id');
            $table->foreign('genre_id')
                  ->references('genre_id')
                  ->on('genres')
                  ->onDelete('cascade');
            
            // Composite primary key
            $table->primary(['comic_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comic_genres');
    }
};
