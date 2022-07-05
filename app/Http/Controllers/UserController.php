<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): ?User
    {
        $id = auth()->id();
        if (empty($id)) {
            return null;
        }

        /** @var User $user */
        $user = User::find($id);
        $user->load('skins', 'skins.skin');

        return $user;
    }

    public function setTradeLink(Request $request): bool
    {
        $id = auth()->id();
        if (empty($id)) {
            throw new AuthorizationException();
        }

        $tradeLink = $request->get('link');
        $this->validateTradeLink($tradeLink);

        /** @var User $user */
        $user = User::find($id);
        $user->setAttribute('steam_trade_link', $tradeLink);
        $user->save();

        return true;
    }

    private function validateTradeLink(string $tradeLink): void
    {
        if (parse_url($tradeLink, PHP_URL_SCHEME) !== 'https') {
            throw new \Exception('Некорректная ссылка');
        }

        if (parse_url($tradeLink, PHP_URL_HOST) !== 'steamcommunity.com') {
            throw new \Exception('Некорректная ссылка');
        }

        if (parse_url($tradeLink, PHP_URL_PATH) !== '/tradeoffer/new/') {
            throw new \Exception('Некорректная ссылка');
        }

        parse_str(parse_url($tradeLink, PHP_URL_QUERY), $parsedQuery);

        if (empty($parsedQuery['partner'])) {
            throw new \Exception('Некорректная ссылка');
        }

        if (empty($parsedQuery['token'])) {
            throw new \Exception('Некорректная ссылка');
        }
    }
}
