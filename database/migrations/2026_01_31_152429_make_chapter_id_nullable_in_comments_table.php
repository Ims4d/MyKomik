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
        Schema::table('comments', function (Blueprint $table) {
            // Drop foreign key first to modify the column
            $table->dropForeign(['chapter_id']);
            $table->unsignedBigInteger('chapter_id')->nullable()->change();
            // Re-add foreign key
            $table->foreign('chapter_id')
                  ->references('chapter_id')
                  ->on('chapters')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['chapter_id']);
            $table->unsignedBigInteger('chapter_id')->nullable(false)->change();
            // Re-add foreign key
            $table->foreign('chapter_id')
                  ->references('chapter_id')
                  ->on('chapters')
                  ->onDelete('cascade');
        });
    }
};
