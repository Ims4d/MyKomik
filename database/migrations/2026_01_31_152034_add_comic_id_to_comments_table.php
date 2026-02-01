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
            $table->unsignedBigInteger('comic_id')->nullable()->after('chapter_id');
            $table->foreign('comic_id')
                  ->references('comic_id')
                  ->on('comics')
                  ->onDelete('cascade');
            $table->index('comic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['comic_id']);
            $table->dropIndex(['comic_id']);
            $table->dropColumn('comic_id');
        });
    }
};
