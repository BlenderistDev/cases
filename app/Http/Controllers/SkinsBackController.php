<?php

namespace App\Http\Controllers;

use App\Services\SkinsBack\Logger\Logger;
use App\Services\SkinsBack\Request\CreateDepositRequest;
use App\Services\SkinsBack\Services\SkinsBackService;
use Illuminate\Http\Request;

class SkinsBackController extends Controller
{
    private const METHOD = 'callback';

    public function index(CreateDepositRequest $createDepositRequest)
    {
        $createDepositRequest->execute();
    }

    public function callback(Request $request, SkinsBackService $skinsBackService, Logger $logger)
    {
        $orderId = $request->get('order_id');
        $logger->log($orderId, $request->toArray(), self::METHOD);
        $skinsBackService->processFromRequest($request);
    }
}
