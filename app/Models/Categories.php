<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img',
    ];

    public function cases(): BelongsToMany
    {
        return $this->belongsToMany(Cases::class);
    }
}
