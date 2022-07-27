<?php

use App\Models\FreeCases;
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
        Schema::create('free_cases_skin', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(FreeCases::class);
            $table->foreignIdFor(Skin::class);
            $table->integer('percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_cases_skin');
    }
};
