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
        Schema::create('skinsback_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('order_id')->nullable();
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->integer('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skinsback_payment_logs');
    }
};
