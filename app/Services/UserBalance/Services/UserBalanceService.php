<?php

declare(strict_types=1);

namespace App\Services\UserBalance\Services;

use App\Events\PaymentEvent;
use App\Models\User;
use App\Services\Loyalty\Loyalty;
use App\Services\UserBalance\Entities\DecreaseBalanceEntity;
use App\Services\UserBalance\Entities\PaymentInfoEntity;
use App\Services\Users\Repositories\UserRepository;
use Exception;

class UserBalanceService
{
    public function __construct(
        private UserRepository $userRepository,
        private Loyalty $loyalty
    )
    {
    }

    public function increaseBalance(PaymentInfoEntity $paymentInfoEntity, bool $needDispatchEvent = true)
    {
        $sum = $this->loyalty->updatePriceWithLoyalty($paymentInfoEntity);
        $this->userRepository->addToBalance($sum, $paymentInfoEntity->getUserId());

        if ($needDispatchEvent) {
            PaymentEvent::dispatch($paymentInfoEntity);
        }
    }

    public function decreaseBalance(DecreaseBalanceEntity $decreaseBalanceEntity)
    {
        if ($this->getUserBalance($decreaseBalanceEntity->getUserId()) < $decreaseBalanceEntity->getAmount()) {
            throw new Exception("Недостаточно средств");
        }

        $this->userRepository->addToBalance(0 - $decreaseBalanceEntity->getAmount(), $decreaseBalanceEntity->getUserId());
    }

    private function getUserBalance($userId): int
    {
        /** @var User $user */
        $user = User::find($userId);

        if (empty($user)) {
            throw new \Exception("Пользователь не найден");
        }

        return (int) $user->balance;
    }
}
