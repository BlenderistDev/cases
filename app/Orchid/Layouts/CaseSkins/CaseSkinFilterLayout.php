<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CaseSkins;

use App\Models\SkinPrice;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CaseSkinFilterLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        $rarityList = SkinPrice::query()
            ->distinct()
            ->select('ru_rarity')
            ->get()
            ->reduce(function($res, $val) {
                $res[$val['ru_rarity']] = $val['ru_rarity'];
                return $res;
            }, []);

        return [
            Input::make('filters.name')
                ->title('Имя'),
            Input::make('filters.priceFrom')
                ->type('number')
                ->title('Цена от'),
            Input::make('filters.priceTo')
                ->type('number')
                ->title('Цена до'),
            Select::make('filters.rarity')
                ->title('Редкость')
                ->multiple()
                ->options($rarityList),
        ];
    }
}
