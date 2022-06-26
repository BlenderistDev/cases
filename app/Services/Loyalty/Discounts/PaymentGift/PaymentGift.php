<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentGift;

use App\Models\Dummy;
use App\Models\User;
use App\Services\Dummy\DummyService;
use App\Services\Loyalty\Discounts\PaymentGift\Repositories\PaymentGiftRepository;
use App\Services\Loyalty\Discounts\PaymentGift\Repositories\PaymentGiftWinnerRepository;

class PaymentGift
{
    public function __construct(
        private PaymentGiftRepository $paymentGiftRepository,
        private DummyService $dummyService,
        private PaymentGiftWinnerRepository $paymentGiftWinnerRepository
    )
    {

    }

    public function raffle(int $paymentGiftId): void
    {
        $paymentGift = \App\Models\PaymentGift::find($paymentGiftId);
        $paymentInfo = $this->paymentGiftRepository->getPaymentGiftInfo();
        $realUserPercent = $paymentInfo->getRealUserPercent();

        // @todo add real users
        $realUserCount = 5;

        if ($realUserPercent !== 0) {
            $participantsCount = (int) ($realUserCount / $realUserPercent * 100);
            $dummies = $this->dummyService->getDummies($participantsCount);
        } else {
            $dummies = $this->dummyService->getDummies(100);
        }

        // @todo add real users
        $participants = array_merge($dummies);


        $winner = $participants[array_rand($participants)];

        if ($winner instanceof Dummy) {
            $this->paymentGiftWinnerRepository->saveForDummy($winner, $paymentGift);
        } elseif ($winner instanceof User) {
            $this->paymentGiftWinnerRepository->saveForUser($winner, $paymentGift);
        } else {
            throw new \Exception("Unknown participant type");
        }
    }

    public function addToRaffle(User $user): void
    {

    }
}
