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
        Schema::create('comments', function (Blueprint $table) {
            $table->id('comment_id');
            
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
            
            // Self-referencing foreign key untuk nested comments (replies)
            $table->unsignedBigInteger('parent_comment_id')->nullable();
            $table->foreign('parent_comment_id')
                  ->references('comment_id')
                  ->on('comments')
                  ->onDelete('cascade');
            
            $table->text('comment_text');
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('chapter_id');
            $table->index('parent_comment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
