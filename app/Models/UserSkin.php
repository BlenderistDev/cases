<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skin_id',
    ];

    protected $hidden = [
        'user_id',
        'skin_id',
        'updated_at',
    ];

    public function skin(): BelongsTo
    {
        return $this->belongsTo(Skin::class);
    }
}
