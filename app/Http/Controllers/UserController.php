<?php

namespace App\Http\Controllers;

use App\Models\User;
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
}
