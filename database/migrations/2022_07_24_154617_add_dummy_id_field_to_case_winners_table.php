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
        Schema::table('case_winners', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Dummy::class)->nullable();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('case_winners', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Dummy::class);
            $table->foreignIdFor(\App\Models\User::class)->change();
        });
    }
};
