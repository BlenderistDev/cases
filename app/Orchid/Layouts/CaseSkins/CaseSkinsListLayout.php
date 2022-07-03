<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CaseSkins;

use App\Models\Skin;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CaseSkinsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'skins';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('img', 'Картинка')
                ->width('150')
                ->render(function (Skin $skin) {
                    // Please use view('path')
                    return "<img src='{$skin->img}'
                              alt='sample'
                              class='mw-100 d-block img-fluid'>";
                }),
            TD::make('market_hash_name', 'Имя на маркете')
                ->render(
                    fn (Skin $skin): string => $skin->market_hash_name
                ),
            TD::make('pivot.percent', 'Процент выпадения')
                ->width('10044px')
                ->render(
                    fn (Skin $skin) => Input::make('skins.' . $skin->pivot->skin_id)
                        ->type('number')
                        ->style("width: 100px")
                        ->required()
                        ->title('Процент выпадения, до 0.001%')
                        ->value($skin->pivot->percent)
                ),
            TD::make('price', 'Стоимость')
                ->render(
                    fn (Skin $skin): string => $skin->price
                ),
            TD::make('active', 'Активность')
                ->render(
                    fn (Skin $skin): int => $skin->active
                ),

            TD::make('delete')
                ->render(function (Skin $skin) {
                    return Button::make('delete')
                        ->method('delete', [
                            'id' => $skin->id,
                        ]);
                }),
        ];
    }
}
