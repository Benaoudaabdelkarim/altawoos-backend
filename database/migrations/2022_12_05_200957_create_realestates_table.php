<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realestates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 1000);
            $table->json('images')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featred')->default(false);
            $table->string('type');
            $table->integer('size')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bethrooms')->nullable();
            $table->decimal('price_sell',9,2)->nullable();
            $table->decimal('price_rent',9,2)->nullable();
            $table->json('localisation');
            $table->json('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('realestates');
    }
};
