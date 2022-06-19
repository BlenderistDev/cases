<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder byName(string $name)
 */
class Options extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
    ];

    /**
     * Scope a query to only include active users.
     *
     * @param  Builder  $query
     * @return void
     */
    public function scopeByName($query, $name)
    {
        $query->where('name', $name);
    }
}
