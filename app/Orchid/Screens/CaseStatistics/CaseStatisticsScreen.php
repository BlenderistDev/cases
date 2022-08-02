<?php

namespace App\Orchid\Screens\CaseStatistics;

use App\Services\Options\OptionsService;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CaseStatisticsScreen extends Screen
{
    public function query(OptionsService $optionsService): iterable
    {
        return [
            'case_offset' => $optionsService->get('case_statistics_case_offset') ?? 0,
            'user_offset' => $optionsService->get('case_statistics_user_offset') ?? 0,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Настройки статистики';
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
        $caseOffset = $request->get('case_offset');
        $userOffset = $request->get('user_offset');
        $optionsService->set('case_statistics_case_offset', $caseOffset);
        $optionsService->set('case_statistics_user_offset', $userOffset);
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
                Input::make('case_offset')
                    ->type('number')
                    ->required()
                    ->title('Фейковое количество открыты кейсов')
                    ->placeholder('Фейковое количество открыты кейсов'),
                Input::make('user_offset')
                    ->type('number')
                    ->required()
                    ->title('Фейковое количество игроков')
                    ->placeholder('Фейковое количество игроков'),
                Button::make('Сохранить')
                    ->method('save')
                    ->type(Color::PRIMARY()),
            ]),
        ];
    }
}
