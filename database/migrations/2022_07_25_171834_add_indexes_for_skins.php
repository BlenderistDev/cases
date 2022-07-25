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
        Schema::table('skin_prices', function (Blueprint $table) {
            $table->index('market_hash_name');
        });

        Schema::table('skins', function (Blueprint $table) {
            $table->index('market_hash_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skin_prices', function (Blueprint $table) {
            $table->dropIndex('market_hash_name');
        });

        Schema::table('skins', function (Blueprint $table) {
            $table->dropIndex('market_hash_name');
        });
    }
};
