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
        Schema::create('user_reading_progress', function (Blueprint $table) {
            // Foreign key to users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Foreign key to chapters
            $table->unsignedBigInteger('chapter_id');
            $table->foreign('chapter_id')
                  ->references('chapter_id')
                  ->on('chapters')
                  ->onDelete('cascade');
            
            $table->timestamp('last_read_at')->useCurrent()->useCurrentOnUpdate();
            
            // Composite primary key
            $table->primary(['user_id', 'chapter_id']);
            
            // Index untuk performa query
            $table->index('last_read_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reading_progress');
    }
};
