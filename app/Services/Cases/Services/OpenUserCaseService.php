<?php

declare(strict_types=1);

namespace App\Services\Cases\Services;

use App\Models\Cases;
use App\Models\CaseWinner;
use App\Models\User;
use App\Models\UserSkin;
use Exception;
use Illuminate\Support\Facades\DB;

class OpenUserCaseService
{
    public function __construct(private OpenCaseService $openCaseService)
    {

    }

    public function openCase(Cases $case, int $userId): UserSkin
    {
        DB::beginTransaction();

        try {
            $this->updateUserBalance($userId, $case->price);
            $winnerSkin = $this->openCaseService->getWinnerSkin($case);
            $userSkin = $this->saveWinner($case->id, $winnerSkin->id, $userId);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
        return $userSkin;
    }

    private function saveWinner(int $caseId, int $skinId, int $userId): UserSkin
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

        return $userSkin;
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
