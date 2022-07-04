<?php

declare(strict_types=1);

namespace App\Services\Cases\Services;

use App\Models\Cases;
use App\Models\CaseWinner;
use App\Models\Skin;
use App\Models\User;
use App\Models\UserSkin;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class OpenCaseService
{
    public function openCase(Cases $case, int $userId): Skin
    {
        DB::beginTransaction();

        try {
            $this->updateUserBalance($userId, $case->price);
            $winnerSkin = $this->getWinnerSkin($case);
            $this->saveWinner($case->id, $winnerSkin->id, $userId);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return $winnerSkin;
    }

    private function saveWinner(int $caseId, int $skinId, int $userId): void
    {
        $winner = new CaseWinner();
        $winner->setAttribute('user_id', $userId);
        $winner->setAttribute('cases_id', $caseId);
        $winner->setAttribute('skin_id', $skinId);

        if (!$winner->save()) {
            throw new \Exception("Не удалось сохранить победителя");
        }

        $userSkin = new UserSkin();
        $userSkin->setAttribute('user_id', $userId);
        $userSkin->setAttribute('skin_id', $skinId);
        if (!$userSkin->save()) {
            throw new \Exception("Не удалось сохранить скин для пользователя");
        }
    }

    /**
     * @param Cases $case
     * @return mixed
     * @throws Exception
     */
    private function getWinnerSkin(Cases $case): Skin
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

    private function updateUserBalance(int $userId, mixed $price)
    {
        $balance = User::find($userId)->balance;
        if ($balance < $price) {
            throw new Exception("Недостаточно средств");
        }

        $balanceDecreaseRes = User::query()
            ->where(['id' => $userId])
            ->update(['balance' => $balance - $price]);

        if (!$balanceDecreaseRes) {
            throw new Exception("Не удалось списать средства");
        }
    }
}
