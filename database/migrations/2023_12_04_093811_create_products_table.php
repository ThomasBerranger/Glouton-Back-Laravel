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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 255);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->mediumText('description')->nullable();
            $table->mediumText('image')->nullable();
            $table->char('nutriscore', 1)->nullable();
            $table->tinyInteger('novagroup')->nullable();
            $table->char('ecoscore', 1)->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->dateTime('added_to_purchase_list_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
