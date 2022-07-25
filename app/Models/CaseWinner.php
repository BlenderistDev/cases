<?php

namespace App\Models;

use App\Services\Loyalty\Discounts\PaymentGift\Entities\WinnerEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseWinner extends Model
{
    use HasFactory;

    protected $appends = ['winner'];

    protected $fillable = [
        'user_id',
        'cases_id',
        'skin_id',
        'dummy_id'
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
        return $this->belongsTo(Cases::class);
    }

    public function dummy(): BelongsTo
    {
        return $this->belongsTo(Dummy::class);
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
