<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinsbackPayment extends Model
{
    use HasFactory;

    public function scopeByTransactionId($query, string $transactionId)
    {
        return $query->where('transaction_id', '=', $transactionId);
    }
}
