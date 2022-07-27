<?php

declare(strict_types=1);

namespace App\Services\FreeCases\Services;

use App\Models\FreeCases;
use App\Models\FreeCasesWinner;
use App\Models\UserSkin;
use App\Services\FreeCases\Repositories\FreeCaseWinnerRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class OpenUserFreeCaseService
{
    public function __construct(
        private OpenFreeCaseService $openFreeCaseService,
        private FreeCaseWinnerRepository $freeCaseWinnerRepository
    ) {
    }

    public function openCase(FreeCases $freeCase, int $userId): UserSkin
    {
        $this->checkUserCanOpenFreeCase($userId, $freeCase);

        DB::beginTransaction();
        try {
            $winnerSkin = $this->openFreeCaseService->getWinnerSkin($freeCase);
            $userSkin = $this->saveWinner($freeCase->id, $winnerSkin->id, $userId);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
        return $userSkin;
    }

    private function saveWinner(int $caseId, int $skinId, int $userId): UserSkin
    {
        $winner = new FreeCasesWinner();
        $winner->setAttribute('user_id', $userId);
        $winner->setAttribute('free_cases_id', $caseId);
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

    private function checkUserCanOpenFreeCase(int $userId, FreeCases $freeCase): void
    {
        if ($this->getLastDayPaymentSum($userId) < $freeCase->price) {
            throw new Exception("Для открытия кейса необходимо пополнить счет на " . $freeCase->price . " рублей за сутки.");
        }

        if ($this->freeCaseWinnerRepository->getOpenedCount($userId, $freeCase->id) > 0) {
            throw new Exception("Нельзя открывать кейс чаще раза в сутки");
        }
    }

    private function getLastDayPaymentSum(int $userId): int
    {
        // @todo fix
        return 0;
    }
}
