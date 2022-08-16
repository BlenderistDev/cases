<?php

namespace App\Http\Controllers;

use App\Services\PayPalych\Requests\CreateBillRequest;
use App\Services\PayPalych\Services\PayPalychService;
use Illuminate\Http\Request;

class PayPalychController extends Controller
{
    public function index(Request $request, CreateBillRequest $createBillRequest)
    {
        $amount = $request->get('amount');
        if (empty($amount)) {
            throw new \Exception("Не указана сумма");
        }

        return ['url' => $createBillRequest->execute((float) $amount, $request->get('promocode', '') ?? '')];
    }

    public function callBack(Request $request, PayPalychService $payPalychService)
    {
        $payPalychService->processFromRequest($request);
    }
}
