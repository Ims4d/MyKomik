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
        Schema::create('ratings', function (Blueprint $table) {
            // Foreign key to users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Foreign key to comics
            $table->unsignedBigInteger('comic_id');
            $table->foreign('comic_id')
                  ->references('comic_id')
                  ->on('comics')
                  ->onDelete('cascade');
            
            $table->integer('rating_value'); // contoh: 1-5 atau 1-10
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Composite primary key
            $table->primary(['user_id', 'comic_id']);
            
            // Index untuk performa query
            $table->index('comic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
