<?php

namespace App\Models;

use App\Services\FreeCases\Repositories\FreeCaseWinnerRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FreeCases extends Model
{
    use HasFactory;

    protected $appends = ['countOpened'];

    protected $fillable = [
        'name',
        'price',
        'img'
    ];

    public function skins(): BelongsToMany
    {
        return $this->belongsToMany(Skin::class);
    }

    public function getCountOpenedAttribute()
    {
        $userId = auth()->id();

        if ($userId) {
            return (new FreeCaseWinnerRepository())->getOpenedCount($userId, $this->id);
        }

        return 0;
    }
}
