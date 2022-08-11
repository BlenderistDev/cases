<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cases extends Model
{
    use HasFactory, SoftDeletes;

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
