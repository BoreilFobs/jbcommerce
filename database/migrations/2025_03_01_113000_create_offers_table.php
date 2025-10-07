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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->foreignId('categories');
            $table->integer('price');
            $table->integer("quantity");
            $table->text('description')->nullable();
            $table->string('images')->nullable();
            // $table->string('img2')->nullable();
            // $table->string('img3')->nullable();
            // $table->string('img4')->nullable();
            // $table->string('img5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
