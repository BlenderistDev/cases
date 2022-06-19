<?php

declare(strict_types=1);

namespace App\Entities;

use JetBrains\PhpStorm\Internal\TentativeType;

class UserInfoEntity implements \JsonSerializable
{
    private string $steamid;
    private string $personaname;
    private string $profileurl;
    private string $avatar;

    public function __construct(
        string $steamid,
        string $personaname,
        string $profileurl,
        string $avatar
    ) {
        $this->steamid = $steamid;
        $this->personaname = $personaname;
        $this->profileurl = $profileurl;
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getSteamid(): string
    {
        return $this->steamid;
    }

    /**
     * @return string
     */
    public function getPersonaname(): string
    {
        return $this->personaname;
    }

    /**
     * @return string
     */
    public function getProfileurl(): string
    {
        return $this->profileurl;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function jsonSerialize()
    {
        return [
            'steamid' => $this->steamid,
            'personaname' => $this->personaname,
            'profileurl' => $this->profileurl,
            'avatar' => $this->avatar,
        ];
    }
}
