<?php

declare(strict_types=1);

namespace App\Services\SkinsBack\Entities;

class CallbackErrorEntity
{
    public function __construct(
        private string $transactionId,
        private string $orderId,
        private int $httpCode
    )
    {
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
