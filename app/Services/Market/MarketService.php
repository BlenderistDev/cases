<?php

declare(strict_types=1);

namespace App\Services\Market;

use App\Services\Market\Request\BuyForRequest;
use App\Services\Market\Request\RubItemsRequest;
use App\Services\Market\Request\RubPriceRequest;

class MarketService
{
    public function __construct(
        private RubItemsRequest $rubItemsRequest,
        private RubPriceRequest $rubPriceRequest,
        private BuyForRequest $buyForRequest
    )
    {
    }

    public function rubItemsRequest(): array
    {
        return $this->rubItemsRequest->makeRequest()['items'] ?? [];
    }

    public function rubPriceRequest(): array
    {
        return $this->rubPriceRequest->makeRequest()['items'] ?? [];
    }

    public function buyFor(int $userSkinId, int $userId)
    {
        $this->buyForRequest->makeRequest($userSkinId, $userId);
    }

}
