<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\NameLoyalty;

use App\Models\User;
use App\Services\Loyalty\Discounts\NameLoyalty\Repositories\NameLoyaltyRepository;
use App\Services\Loyalty\Exceptions\NoAuthException;
use App\Services\Loyalty\Exceptions\NoSteamIdException;
use App\Services\Loyalty\Exceptions\NoSteamInfo;
use App\Services\Loyalty\LoyaltyInterface;
use App\Services\SteamInfo\SteamUserInfoService;
use App\Services\UserBalance\Entities\PaymentInfoEntity;
use function auth;

class NameLoyalty implements LoyaltyInterface
{
    public function __construct(private SteamUserInfoService $steamUserInfoService, private NameLoyaltyRepository $nameLoyaltyRepository)
    {
    }

    public function updatePrice(PaymentInfoEntity $paymentInfoEntity): int
    {
        $user = User::find($paymentInfoEntity->getUserId());
        if (empty($user)) {
            throw new NoAuthException();
        }

        if (empty($user->steamid)) {
            throw new NoSteamIdException();
        }

        $steamId = $user->steamid;

        $steamInfo = $this->steamUserInfoService->getUserInfo($steamId);

        if (empty($steamInfo)) {
            throw new NoSteamInfo();
        }

        $loyaltyInfo = $this->nameLoyaltyRepository->getLoyaltyInfo();

        $amount = $paymentInfoEntity->getAmount();

        if (!str_contains(strtoupper($steamInfo->getPersonaname()), strtoupper($loyaltyInfo->getPattern()))) {
            return $amount;
        }

        $bonus = (int) ($amount / 100 * $loyaltyInfo->getValue());

        return $amount - $bonus;
    }
}
