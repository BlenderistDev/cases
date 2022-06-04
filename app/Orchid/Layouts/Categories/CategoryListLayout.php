<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Categories;

use App\Models\Cases;
use App\Models\Categories;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('img', 'Картинка')
                ->width('150')
                ->render(function (Categories $category) {
                    return "<img src='{$category->img}'
                              alt='sample'
                              class='mw-100 d-block img-fluid'>";
                }),
            TD::make('name', 'Имя')
                ->width(100)
                ->render(
                    fn (Categories $item): string => $item->name
                ),
            TD::make('edit')
                ->render(function (Categories $category) {
                    return Link::make(__('Edit'))
                        ->route('platform.systems.categories.edit', $category->id)
                        ->icon('pencil');
                }),
            TD::make('delete')
                ->render(function (Categories $category) {
                    return Button::make('delete')
                        ->method('delete', [
                            'id' => $category->id,
                        ]);
                })
        ];
    }
}
