<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Invisnik\LaravelSteamAuth\SteamInfo;

class SteamAuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/me';


    /**
     * AuthController constructor.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Redirect the user to the authentication page
     *
     * @return RedirectResponse
     */
    public function redirectToSteam(): RedirectResponse
    {
        return $this->steam->redirect();
    }

    /**
     * Get user info and log in
     *
     * @return RedirectResponse|Redirector
     * @throws GuzzleException
     */
    public function handle()
    {

        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();

            if (!is_null($info)) {
                $user = $this->findOrNewUser($info);

                Auth::login($user, true);

                return redirect($this->redirectURL); // redirect to site
            }
        }

        return $this->redirectToSteam();
    }

    public function logout()
    {
        Auth::logout();
        return redirect($this->redirectURL);
    }

    /**
     * Getting user by info or created if not exists
     *
     * @param $info
     * @return User
     */
    protected function findOrNewUser(SteamInfo $info)
    {
        $user = User::where('steamid', $info->steamID64)->first();

        if (!is_null($user)) {
            return $user;
        }

        return User::create([
            'name' => $info->personaname,
            'username' => $info->personaname,
            'avatar' => $info->avatarfull,
            'steamid' => $info->steamID64,
            'password' => "",
            'email' => $info->steamID64
        ]);
    }
}
