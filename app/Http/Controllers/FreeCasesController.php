<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\FreeCases;
use App\Services\Cases\Services\OpenUserCaseService;
use App\Services\FreeCases\Services\OpenUserFreeCaseService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class FreeCasesController extends Controller
{
    public function index()
    {
        return [
            'items' => FreeCases::with('skins')->get(),
        ];
    }

    public function open(OpenUserFreeCaseService $openUserFreeCaseService, Request $request): string
    {
        $userId = auth()->id();

        if (empty($userId)) {
            throw new AuthorizationException();
        }

        $caseId = $request->get('id');
        if (empty($caseId)) {
            throw new \Exception("Id кейс не задан");
        }

        $case = FreeCases::find($caseId);
        if (empty($case)) {
            throw new \Exception("Кейс не найден");
        }

        return $openUserFreeCaseService->openCase($case, $userId)->load('skin')->toJson();
    }
}
