<?php

namespace App\Http\Controllers;

use App\Services\Settings\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(SettingsService $settingsService): array
    {
        return [
            'email' => $settingsService->getEmail()
        ];
    }
}
