<?php

namespace App\Http\Controllers;

use App\Models\CasesSkin;
use App\Models\Skin;
use Illuminate\Http\Request;

class CaseOpenHistoryController extends Controller
{
    private const DEFAULT_SKIN_COUNT = 100;

    public function index(Request $request)
    {
        $limit = $request->get('limit', self::DEFAULT_SKIN_COUNT);
        return CasesSkin::query()
            ->select('skin_id')
            ->with('skin')
            ->distinct('skin_id')
            ->limit($limit)
            ->inRandomOrder()
            ->get()
            ->map(fn (CasesSkin $casesSkin): Skin => $casesSkin->skin);
    }
}
