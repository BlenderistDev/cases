<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'steam_item_id',
        'steam_item_second_id',
        'steam_full_id',
        'price',
        'buy_order',
        'avg_price',
        'popularity_7d',
        'market_hash_name',
        'ru_name',
        'ru_rarity',
        'ru_quality',
        'text_color',
        'bg_color',
        'active',
    ];

    public function getImgAttribute(): string
    {
        $id = $this->steam_full_id;
        return "https://steamcommunity-a.akamaihd.net/economy/image/class/730/$id/60fx60f.png";
    }
}
