<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products_recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('recipe_id')->references('id')->on('recipes');
            $table->float('quantity');
            $table->string('quantity_unity', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products_recipes');
    }
};
