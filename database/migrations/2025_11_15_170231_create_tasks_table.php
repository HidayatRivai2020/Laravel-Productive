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
        Schema::create('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('objective_id');
            $table->integer('id');
            $table->text('detail')->nullable();
            $table->string('image')->nullable();
            $table->integer('status');
            
            $table->primary(['objective_id', 'id']);
            $table->foreign('objective_id')->references('id')->on('objectives')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
