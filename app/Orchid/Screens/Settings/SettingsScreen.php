<?php

namespace App\Orchid\Screens\Settings;

use App\Services\Options\OptionsService;
use App\Services\Settings\Services\SettingsService;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class SettingsScreen extends Screen
{
    public function query(SettingsService $settingsService): iterable
    {
        return [
            'email' => $settingsService->getEmail(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Настройки сайта';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    public function save(Request $request, OptionsService $optionsService)
    {
        $email = $request->get('email');

        $optionsService->set(SettingsService::SITE_EMAIL_KEY, $email);
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('email')
                    ->required()
                    ->title('Email администратора')
                    ->placeholder('Email администратора'),
                Button::make('Сохранить')
                    ->method('save')
                    ->type(Color::PRIMARY()),
            ]),
        ];
    }
}
