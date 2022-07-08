<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CasesSkin extends Model
{
    use HasFactory;

    protected $table = 'cases_skin';

    protected $fillable = [
    ];

    public function skin(): BelongsTo
    {
        return $this->belongsTo(Skin::class);
    }
}
