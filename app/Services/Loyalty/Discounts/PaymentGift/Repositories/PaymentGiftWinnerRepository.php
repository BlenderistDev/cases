<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentGift\Repositories;

use App\Models\Dummy;
use App\Models\PaymentGift;
use App\Models\PaymentGiftWinner;
use App\Models\User;

class PaymentGiftWinnerRepository
{
    public function saveForDummy(Dummy $dummy, PaymentGift $paymentGift): void
    {
        $winner = new PaymentGiftWinner();
        $winner->dummy()->associate($dummy);
        $winner->paymentGift()->associate($paymentGift);
        $winner->save();
    }

    public function saveForUser(User $user, PaymentGift $paymentGift): void
    {
        $winner = new PaymentGiftWinner();
        $winner->dummy()->associate($user);
        $winner->paymentGift()->associate($paymentGift);
        $winner->save();
    }
}
