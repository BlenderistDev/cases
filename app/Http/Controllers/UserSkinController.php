<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSkin;
use App\Services\Market\MarketService;
use App\Services\UserBalance\Entities\PaymentInfoEntity;
use App\Services\UserBalance\Services\UserBalanceService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSkinController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        if (empty($userId)) {
            throw new AuthorizationException();
        }

        return UserSkin::query()
            ->where('user_id', '=', $userId)
            ->with('skin')
            ->get();
    }

    public function sell(Request $request, UserBalanceService $userBalanceService): bool
    {
        DB::transaction(function () use ($request, $userBalanceService) {
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

            $paymentEntity = new PaymentInfoEntity(
                null,
                (int) ($userSkin->skin()->first()->price * 100),
                'skin_sell',
                $userId
            );

            $userBalanceService->increaseBalance($paymentEntity);

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
