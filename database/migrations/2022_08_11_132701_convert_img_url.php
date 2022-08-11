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
        $this->convert(\App\Models\Cases::all());
        $this->convert( \App\Models\Dummy::all());
        $this->convert( \App\Models\FreeCases::all());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return void
     */
    private function convert(\Illuminate\Database\Eloquent\Collection $items): void
    {
        foreach ($items as $item) {
            $item->img = str_replace('http://142.93.237.47', 'https://azidrop.pro', $item->img);
            $item->save();
        }
    }
};
