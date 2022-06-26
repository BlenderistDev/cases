<?php

declare(strict_types=1);

namespace App\Services\Dummy\Repository;

use App\Models\Dummy;
use Illuminate\Database\Eloquent\Collection;

class DummyRepository
{
    public function getList(): Collection
    {
        return Dummy::all();
    }
}
