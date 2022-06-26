<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Dummy;

use App\Models\Cases;
use App\Models\Dummy;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class DummyListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'dummy';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('img', 'Картинка')
                ->width('150')
                ->render(function (Dummy $dummy) {
                    // Please use view('path')
                    return "<img src='{$dummy->img}'
                              alt='sample'
                              class='mw-100 d-block img-fluid'>";
                }),
            TD::make('name', 'Имя')
                ->width(100)
                ->render(
                    fn (Dummy $item): string => $item->name
                ),
            TD::make('active', 'Активность')
                ->width(10)
                ->render(
                    fn (Dummy $item) => $item->active
                ),
            TD::make('delete')
                ->render(function (Dummy $dummy) {
                    return Button::make('delete')
                        ->method('delete', [
                            'id' => $dummy->id,
                        ]);
                }),
            TD::make('edit')
                ->render(function (Dummy $dummy) {
                    return Link::make(__('Edit'))
                        ->route('platform.systems.dummy.edit', $dummy->id)
                        ->icon('pencil');
                })
        ];
    }
}
