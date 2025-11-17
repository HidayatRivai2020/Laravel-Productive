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
        Schema::create('objectives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('content_id');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('content_id')->references('id')->on('contents')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->dropForeign(['content_id']);
        });

        Schema::dropIfExists('objectives');
    }
};
