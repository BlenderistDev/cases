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
        Schema::create('skin_prices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('steam_item_id');
            $table->bigInteger('steam_item_second_id');
            $table->string('steam_full_id')->unique();
            $table->decimal('price', 10);
            $table->decimal('buy_order', 10);
            $table->decimal('avg_price', 14, 6)->nullable();
            $table->integer('popularity_7d')->nullable();
            $table->string('market_hash_name', 512);
            $table->string('ru_name', 512)->nullable();
            $table->string('ru_rarity')->nullable();
            $table->string('ru_quality')->nullable();
            $table->string('text_color')->nullable();
            $table->string('bg_color')->nullable();
            $table->boolean('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skin_prices');
    }
};
