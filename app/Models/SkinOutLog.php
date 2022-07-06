<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinOutLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skin_id',
        'price',
        'custom_id',
        'trade_link',
        'request',
        'response'
    ];
}
