<?php

declare(strict_types=1);

namespace App\Services\Cases\Services;

use App\Models\Cases;
use App\Models\Skin;
use Exception;

class OpenCaseService
{
    /**
     * @param Cases $case
     * @return mixed
     * @throws Exception
     */
    public function getWinnerSkin(Cases $case): Skin
    {
        $skins = $case
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
