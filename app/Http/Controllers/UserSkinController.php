<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSkin;
use App\Services\Market\MarketService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSkinController extends Controller
{
    public function sell(Request $request): bool
    {
        DB::transaction(function () use ($request) {
            $userId = auth()->id();
            if (empty($userId)) {
                throw new AuthorizationException();
            }

            $userSkinId = $request->get('id');

            /** @var UserSkin $userSkin */
            $userSkin = UserSkin::find($userSkinId);
            if (empty($userSkin)) {
                throw new \Exception("Предмет не найден");
            }
            if ($userSkin->user_id !== $userId) {
                throw new AuthorizationException();
            }

            $user = User::find($userId);
            if (empty($user)) {
                throw new \Exception("Пользователь не найден");
            }

            $user->balance = $user->balance + $userSkin->skin()->first()->price;
            if (!$user->save()) {
                throw new \Exception("Ошибка при начислении баланса");
            }

            if (!$userSkin->delete()) {
                throw new \Exception("Ошибка продажи скина");
            }
        });

        return true;
    }

    public function out(Request $request, MarketService $marketService): bool
    {
        if (auth()->guest()) {
            throw new AuthorizationException();
        }
        $userId = auth()->id();

        $skinId = $request->get('id');
        if (empty($skinId)) {
            throw new \Exception('no id');
        }
        $marketService->buyFor($skinId, $userId);

        return true;
    }
}

//175548441
//dN4rWm18

