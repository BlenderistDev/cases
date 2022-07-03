<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGift extends Model
{
    use HasFactory;

    protected $fillable = ['min, max', 'active', 'skin_id'];

    public function skin(): BelongsTo
    {
        return $this->belongsTo(Skin::class);
    }
}
