<?php

namespace App\Orchid\Screens\Cases;

use App\Models\Cases;
use App\Orchid\Layouts\Cases\CaseListLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CasesListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'cases' => Cases::all()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Кейсы';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить')
                ->icon('plus')
                ->route('platform.systems.cases.create'),
        ];
    }

    public function delete(Cases $case)
    {
        $case->delete();
        redirect()->route('platform.cases');
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            CaseListLayout::class,
            Layout::rows([
                Group::make(
                    [
                        Button::make('Сохранить')->method('save')->type(Color::PRIMARY()),
                    ]
                )->autoWidth(),
            ]),
        ];
    }
}
