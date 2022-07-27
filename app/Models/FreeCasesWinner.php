<?php

namespace App\Models;

use App\Services\Loyalty\Discounts\PaymentGift\Entities\WinnerEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeCasesWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cases_id',
        'skin_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skin(): BelongsTo
    {
        return $this->belongsTo(Skin::class);
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(FreeCases::class);
    }
}
