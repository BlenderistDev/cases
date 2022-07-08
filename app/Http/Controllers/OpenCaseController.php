<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Services\Cases\Services\OpenCaseService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class OpenCaseController extends Controller
{
    public function index(OpenCaseService $openCaseService, Request $request): string
    {
        $userId = auth()->id();

        if (empty($userId)) {
            throw new AuthorizationException();
        }

        $caseId = $request->get('id');
        if (empty($caseId)) {
            throw new \Exception("Id кейс не задан");
        }

        $case = Cases::find($caseId);
        if (empty($case)) {
            throw new \Exception("Кейс не найден");
        }

        return $openCaseService->openCase($case, $userId)->load('skin')->toJson();
    }
}
