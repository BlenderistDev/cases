<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Cases;

use App\Models\Cases;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CaseListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'cases';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('img', 'Картинка')
                ->width('150')
                ->render(function (Cases $case) {
                    // Please use view('path')
                    return "<img src='{$case->img}'
                              alt='sample'
                              class='mw-100 d-block img-fluid'>";
                }),
            TD::make('name', 'Имя')
                ->width(100)
                ->render(
                    fn (Cases $item): string => $item->name
                ),
            TD::make('price', 'Стоимость')
                ->width(100)
                ->render(
                    fn (Cases $item): int => $item->price
                ),
            TD::make('delete')
                ->render(function (Cases $case) {
                    return Button::make('delete')
                        ->method('delete', [
                            'id' => $case->id,
                        ]);
                }),
            TD::make('edit')
                ->render(function (Cases $case) {
                    return Link::make(__('Edit'))
                        ->route('platform.systems.cases.edit', $case->id)
                        ->icon('pencil');
                }),
            TD::make('edit_skins', 'скины')
                ->render(function (Cases $case) {
                    return Link::make(__('Edit'))
                        ->route('platform.systems.case.skins.list', $case->id)
                        ->icon('pencil');
                })
        ];
    }
}
