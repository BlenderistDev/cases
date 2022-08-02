<?php

declare(strict_types=1);

namespace App\Services\SkinsBack\Services;

use App\Services\SkinsBack\Request\CallbackErrorListRequest;
use App\Services\SkinsBack\Request\OrderStatusRequest;

class MissingCallbackService
{
    public function __construct(
        private CallbackErrorListRequest $callbackErrorListRequest,
        private OrderStatusRequest $orderStatusRequest
    )
    {
    }

    public function checkMissingCallbacks()
    {
        $missingCallbackList = $this->callbackErrorListRequest->execute();

        foreach ($missingCallbackList as $payment) {
            $this->orderStatusRequest->execute($payment->getTransactionId(), $payment->getOrderId());
        }
    }
}
