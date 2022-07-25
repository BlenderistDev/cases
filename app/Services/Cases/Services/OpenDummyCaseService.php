<?php

declare(strict_types=1);

namespace App\Services\Cases\Services;

use App\Models\Cases;
use App\Models\CaseWinner;
use App\Services\Dummy\DummyService;

class OpenDummyCaseService
{
    public function __construct(
        private OpenCaseService $openCaseService,
        private DummyService $dummyService
    ) {
    }

    public function openCase(): CaseWinner
    {
        $dummy = $this->dummyService->getDummies(1);
        $case = Cases::all()->random(1)->first();

        if (empty($dummy)) {
            throw new \Exception("no dummy");
        }

        $winnerSkin = $this->openCaseService->getWinnerSkin($case);

        return $this->saveWinner($case->id, $winnerSkin->id, $dummy[0]->id);
    }

    private function saveWinner(int $caseId, int $skinId, int $dummyId): CaseWinner
    {
        $winner = new CaseWinner();
        $winner->setAttribute('dummy_id', $dummyId);
        $winner->setAttribute('cases_id', $caseId);
        $winner->setAttribute('skin_id', $skinId);

        if (!$winner->save()) {
            throw new \Exception("Не удалось сохранить победителя");
        }

        return $winner;
    }
}
