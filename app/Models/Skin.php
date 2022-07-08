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

    protected $appends = ['img', 'name', 'rarity', 'short_name'];

    public function prices(): HasMany
    {
        return $this->hasMany(SkinPrice::class, 'market_hash_name', 'market_hash_name');
    }

    public function getImgAttribute(): string
    {
        $id = $this->prices()->first()->steam_full_id;
        return "https://steamcommunity-a.akamaihd.net/economy/image/class/730/$id/60fx60f.png";
    }

    public function getNameAttribute(): string
    {
        $price = $this->prices()->first();
        return empty($price->ru_name) ? $this->market_hash_name : $price->ru_name;
    }

    public function getRarityAttribute(): string
    {
        $price = $this->prices()->first();
        return (string) $price->ru_rarity;
    }

    public function getShortNameAttribute(): string
    {
        return preg_replace('/.?\(.*\)/', '', $this->name);
    }
}
