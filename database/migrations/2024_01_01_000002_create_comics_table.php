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
        Schema::create('comics', function (Blueprint $table) {
            $table->id('comic_id');
            $table->string('title', 255);
            $table->text('synopsis')->nullable();
            $table->string('author', 100)->nullable();
            $table->string('cover_image_url', 500)->nullable();
            $table->enum('status', ['ongoing', 'completed', 'hiatus'])->default('ongoing');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};
