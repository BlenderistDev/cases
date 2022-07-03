<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skin extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_hash_name',
        'price',
        'volume',
        'active',
    ];

    protected $appends = ['img'];

    public function prices(): HasMany
    {
        return $this->hasMany(SkinPrice::class, 'market_hash_name', 'market_hash_name');
    }

    public function getImgAttribute(): string
    {
        $id = $this->prices()->first()->steam_full_id;
        return "https://steamcommunity-a.akamaihd.net/economy/image/class/730/$id/60fx60f.png";
    }
}
