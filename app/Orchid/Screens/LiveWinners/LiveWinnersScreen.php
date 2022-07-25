<?php

namespace App\Orchid\Screens\LiveWinners;

use App\Services\Options\OptionsService;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class LiveWinnersScreen extends Screen
{
    public function query(OptionsService $optionsService): iterable
    {
        return [
            'chance' => $optionsService->get('fake_case_open_chance') ?? 0,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Лайв лента';
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
        $chance = $request->get('chance');
        $optionsService->set('fake_case_open_chance', $chance);
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
                Input::make('chance')
                    ->type('number')
                    ->required()
                    ->max(0)
                    ->max(100)
                    ->title('Вероятность фейкового розыгрыша в минуту')
                    ->placeholder('процент реальных игроков'),
                Button::make('Сохранить')
                    ->method('save')
                    ->type(Color::PRIMARY()),
            ]),
        ];
    }
}
