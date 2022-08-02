<?php

declare(strict_types=1);

namespace App\Services\Users\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function addToBalance(int $sumToAdd, int $userId): void
    {
        /** @var User $user */
        $user = User::find($userId);

        if (empty($user)) {
            throw new \Exception("Пользователь не найден");
        }

        $user->balance += $sumToAdd;

        if(!$user->save()) {
            throw new \Exception("Не удалось изменить баланс");
        }
    }
}
