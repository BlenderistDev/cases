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
        $fromId = $request->get('from', 0);

        $items = CaseWinner::query()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->with('skin')
            ->where('id', '>', $fromId)
            ->get();

        if ($items->isNotEmpty()) {
            $lastId = $items->first()->id;
        }

        return [
            'items' => $items->map(fn (CaseWinner $casesSkin): Skin => $casesSkin->skin),
            'lastId' => $lastId ?? 0,
        ];
    }
}
