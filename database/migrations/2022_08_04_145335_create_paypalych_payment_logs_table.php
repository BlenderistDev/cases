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
        Schema::create('paypalych_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('order_id')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('paypalych_payment_logs');
    }
};
