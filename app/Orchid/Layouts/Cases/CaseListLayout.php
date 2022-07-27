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
    public function __construct(private string $paramName)
    {
    }

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
                ->render(function ($case) {
                    // Please use view('path')
                    return "<img src='{$case->img}'
                              alt='sample'
                              class='mw-100 d-block img-fluid'>";
                }),
            TD::make('name', 'Имя')
                ->width(100)
                ->render(
                    fn ($item): string => $item->name
                ),
            TD::make('price', 'Стоимость')
                ->width(100)
                ->render(
                    fn ($item): int => $item->price
                ),
            TD::make('delete')
                ->render(function ($case) {
                    return Button::make('delete')
                        ->method('delete', [
                            'id' => $case->id,
                        ]);
                }),
            TD::make('edit')
                ->render(function ($case) {
                    return Link::make(__('Edit'))
                        ->route('platform.systems.' . $this->paramName . '.edit', $case->id)
                        ->icon('pencil');
                }),
            TD::make('edit_skins', 'скины')
                ->render(function ($case) {
                    return Link::make(__('Edit'))
                        ->route('platform.systems.' . $this->paramName . '.skins.list', $case->id)
                        ->icon('pencil');
                })
        ];
    }
}
