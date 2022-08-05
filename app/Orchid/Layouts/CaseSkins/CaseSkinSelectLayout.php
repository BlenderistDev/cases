<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CaseSkins;

use App\Models\Skin;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CaseSkinSelectLayout extends Rows
{
    private const SKIN_LIMIT = 1000;

    public function __construct(
        private string $name,
        private int $priceFrom,
        private int $priceTo,
        private array $rarity
    )
    {

    }
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        $query = DB::table('skins');

        if ($this->rarity) {
            $query
                ->join('skin_prices', 'skin_prices.market_hash_name', '=', 'skins.market_hash_name')
                ->where('skin_prices.ru_rarity', '=', $this->rarity);
        }

        $query
            ->where('skins.market_hash_name', 'LIKE', '%' . $this->name . '%')
            ->limit(self::SKIN_LIMIT);

        if ($this->priceFrom) {
            $query
                ->where('skins.price', '>=', $this->priceFrom);
        }

        if ($this->priceTo) {
            $query
                ->where('skins.price', '<=', $this->priceTo);
        }

        $skins = $query->get();

        foreach ($skins as $skin) {
            $options[(string) $skin->id] = $skin->market_hash_name . ' - ' . $skin->price;
        }

        return [
            Select::make('skins')
                ->empty('Не выбрано', '0')
                ->options($options)
                ->multiple()
        ];
    }
}
