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
        Schema::create('pages', function (Blueprint $table) {
            $table->id('page_id');
            
            // Foreign key to comics
            $table->unsignedBigInteger('comic_id');
            $table->foreign('comic_id')
                  ->references('comic_id')
                  ->on('comics')
                  ->onDelete('cascade');
            
            // Foreign key to chapters
            $table->unsignedBigInteger('chapter_id');
            $table->foreign('chapter_id')
                  ->references('chapter_id')
                  ->on('chapters')
                  ->onDelete('cascade');
            
            $table->integer('page_number');
            $table->string('image_url', 500);
            
            // Index untuk performa query
            $table->index(['chapter_id', 'page_number']);
            
            // Unique constraint: tidak boleh ada page_number yang sama dalam 1 chapter
            $table->unique(['chapter_id', 'page_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
