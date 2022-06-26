<?php

namespace App\Models;

use App\Services\Loyalty\Discounts\PaymentGift\Entities\WinnerEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGiftWinner extends Model
{
    use HasFactory;

    protected $appends = ['winner'];

    protected $hidden = ['dummy_id', 'payment_gift_id', 'user_id', 'user', 'dummy'];

    public function dummy(): BelongsTo
    {
        return $this->belongsTo(Dummy::class);
    }

    public function paymentGift(): BelongsTo
    {
        return $this->belongsTo(PaymentGift::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, );
    }

    public function getWinnerAttribute(): ?WinnerEntity
    {
        $this->load('user', 'dummy');

        if ($this->user()->exists()) {
            return new WinnerEntity(
                $this->user()
                    ->get('name'),
                $this->user()
                    ->get('avatar')
            );
        } elseif ($this->dummy()->exists()) {
            return new WinnerEntity(
                $this->dummy()
                    ->first()
                    ->getAttribute('name'),
                $this->dummy()
                    ->first()
                    ->getAttribute('img')
            );
        }

        return null;
    }
}
