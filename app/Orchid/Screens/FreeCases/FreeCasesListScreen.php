<?php

namespace App\Orchid\Screens\FreeCases;

use App\Models\Cases;
use App\Models\FreeCases;
use App\Orchid\Layouts\Cases\CaseListLayout;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class FreeCasesListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'cases' => FreeCases::all()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Бесплатные кейсы';
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
                ->route('platform.systems.freeCases.create'),
        ];
    }

    public function delete(FreeCases $freeCases)
    {
        $freeCases->delete();
        redirect()->route('platform.freeCases');
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            new CaseListLayout('freeCases'),
        ];
    }
}
