<?php

use App\Models\Skin;
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
        Schema::table('payment_gifts', function (Blueprint $table) {
            $table->foreignIdFor(Skin::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_gifts', function (Blueprint $table) {
            $table->dropColumn('skin_id');
        });
    }
};
