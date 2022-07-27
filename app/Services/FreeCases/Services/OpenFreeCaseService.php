<?php

declare(strict_types=1);

namespace App\Services\FreeCases\Services;

use App\Models\Cases;
use App\Models\FreeCases;
use App\Models\Skin;
use Exception;

class OpenFreeCaseService
{
    /**
     * @param Cases $case
     * @return mixed
     * @throws Exception
     */
    public function getWinnerSkin(FreeCases $freeCases): Skin
    {
        $skins = $freeCases
            ->skins()
            ->withPivot('percent')
            ->get()
            ->keyBy('id');

        $participants = [];

        foreach ($skins as $id => $skin) {
            $percent = $skin->pivot->percent;
            for ($i = 0; $i < $percent; $i++) {
                $participants [] = $id;
            }
        }

        if (empty($participants)) {
            throw new Exception("Для кейса не найдены скины");
        }

        $winnerSkinId = $participants[array_rand($participants)];

        return $skins[$winnerSkinId];
    }
}
