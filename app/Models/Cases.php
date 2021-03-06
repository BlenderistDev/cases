<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cases extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'img'
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categories::class);
    }

    public function skins(): BelongsToMany
    {
        return $this->belongsToMany(Skin::class);
    }
}
