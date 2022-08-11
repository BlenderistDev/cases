<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['min, max', 'active', 'skin_id'];

    public function skin(): BelongsTo
    {
        return $this->belongsTo(Skin::class);
    }
}
