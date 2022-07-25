<?php

namespace App\Http\Controllers;

use App\Models\CaseWinner;
use App\Models\Skin;
use Illuminate\Http\Request;

class CaseOpenHistoryController extends Controller
{
    private const DEFAULT_SKIN_COUNT = 100;

    public function index(Request $request)
    {
        $limit = $request->get('limit', self::DEFAULT_SKIN_COUNT);

        return CaseWinner::query()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->with('skin')
            ->get()
            ->map(fn (CaseWinner $casesSkin): Skin => $casesSkin->skin);
    }
}
