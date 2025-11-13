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
        // Change contents.category_id FK from cascadeOnDelete to restrictOnDelete
        Schema::table('contents', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
        });

        // Change objectives.content_id FK from cascadeOnDelete to restrictOnDelete
        Schema::table('objectives', function (Blueprint $table) {
            $table->dropForeign(['content_id']);
            $table->foreign('content_id')->references('id')->on('contents')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->dropForeign(['content_id']);
            $table->foreign('content_id')->references('id')->on('contents')->cascadeOnDelete();
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });
    }
};
