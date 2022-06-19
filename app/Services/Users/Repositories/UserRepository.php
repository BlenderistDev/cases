<?php

declare(strict_types=1);

namespace App\Services\Users\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function addToBalance(int $sumToAdd): void
    {
        /** @var User $user */
        $user = Auth::user();
        $user->balance += $sumToAdd;
        $user->save();
    }
}
