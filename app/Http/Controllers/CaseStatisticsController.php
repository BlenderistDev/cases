<?php

namespace App\Http\Controllers;

use App\Models\CaseWinner;
use App\Services\Options\OptionsService;
use Illuminate\Http\Request;

class CaseStatisticsController extends Controller
{
    public function index(OptionsService $optionsService)
    {
        $caseOffset = $optionsService->get('case_statistics_case_offset') ?? 0;
        $userOffset = $optionsService->get('case_statistics_user_offset') ?? 0;

        $userCount = CaseWinner::query()
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count();

        $caseCount = CaseWinner::query()->count();

        return [
            'case' => $caseOffset + $caseCount,
            'user' => $userOffset + $userCount,
        ];
    }
}
