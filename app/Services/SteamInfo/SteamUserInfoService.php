<?php

declare(strict_types=1);

namespace App\Services\SteamInfo;

use App\Entities\UserInfoEntity;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Invisnik\LaravelSteamAuth\SteamAuth;

class SteamUserInfoService
{
    const URL = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/';

    private Client $client;

    public function __construct(SteamAuth $steam, Client $client)
    {
        $this->steam = $steam;
        $this->client = $client;
    }

    public function getUserInfo(string $steamId): ?UserInfoEntity
    {
        $response = $this->client->request('GET', self::URL, [
            RequestOptions::QUERY => [
                'key' => getenv('STEAM_API_KEY'),
                'steamids' => $steamId,
            ]
        ]);

        $obj = json_decode($response->getBody()->getContents());

        if (!$obj || (empty($obj->response->players[0]))) {
            return null;
        }

        return new UserInfoEntity(
            $obj->response->players[0]->steamid,
            $obj->response->players[0]->personaname,
            $obj->response->players[0]->profileurl,
            $obj->response->players[0]->avatar
        );
    }
}
