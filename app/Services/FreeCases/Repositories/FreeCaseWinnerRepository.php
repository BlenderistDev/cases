<?php

declare(strict_types=1);

namespace App\Services\FreeCases\Repositories;

use App\Models\FreeCasesWinner;
use Carbon\Carbon;

class FreeCaseWinnerRepository
{
    public function getOpenedCount(int $userId, int $caseId): int
    {
        return FreeCasesWinner::query()
            ->where('user_id', '=', $userId)
            ->where('free_cases_id', '=', $caseId)
            ->where('created_at', '>', Carbon::now()->subDay())
            ->count();
    }
}
