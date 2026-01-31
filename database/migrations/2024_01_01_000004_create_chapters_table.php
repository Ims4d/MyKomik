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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id('chapter_id');
            
            // Foreign key to comics
            $table->unsignedBigInteger('comic_id');
            $table->foreign('comic_id')
                  ->references('comic_id')
                  ->on('comics')
                  ->onDelete('cascade');
            
            $table->integer('chapter_number');
            $table->string('title', 255)->nullable();
            $table->date('release_date')->nullable();
            
            // Index untuk performa query
            $table->index(['comic_id', 'chapter_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
