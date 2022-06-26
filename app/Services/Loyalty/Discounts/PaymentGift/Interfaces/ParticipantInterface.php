<?php
declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentGift\Interfaces;

interface ParticipantInterface
{
    public function getName(): string;
    public function getAvatar(): string;

}
