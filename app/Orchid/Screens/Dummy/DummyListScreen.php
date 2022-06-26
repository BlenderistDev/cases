<?php

namespace App\Orchid\Screens\Dummy;

use App\Models\Dummy;
use App\Orchid\Layouts\Dummy\DummyListLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class DummyListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'dummy' => Dummy::all()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Фейковые пользователи';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить')
                ->icon('plus')
                ->route('platform.systems.dummy.create'),
        ];
    }

    public function delete(Dummy $dummy)
    {
        $dummy->delete();
        redirect()->route('platform.dummy');
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
                Group::make(
                    [
                        Button::make('Применить')->method('applyFilters')->type(Color::SECONDARY()),
                        Button::make('Сбросить')->method('resetFilters')->type(Color::ERROR())->tabindex(3),
                    ]
                )->autoWidth(),
            ]),
            DummyListLayout::class,
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
