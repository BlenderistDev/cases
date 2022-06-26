<?php

use App\Models\Dummy;
use App\Models\PaymentGift;
use App\Models\User;
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
        Schema::create('payment_gift_winners', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(PaymentGift::class);
            $table->foreignIdFor(Dummy::class)->nullable();
            $table->foreignIdFor(User::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gift_winners');
    }
};
