<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CaseSkins;

use App\Models\Skin;
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
        if ($this->rarity) {
            $query = Skin::byRarity($this->rarity);
        } else {
            $query = Skin::query();
        }

        $query
            ->where('market_hash_name', 'LIKE', '%' . $this->name . '%')
            ->limit(self::SKIN_LIMIT);

        if ($this->priceFrom) {
            $query
                ->where('price', '>=', $this->priceFrom);
        }

        if ($this->priceTo) {
            $query
                ->where('price', '<=', $this->priceTo);
        }


        $skins = $query->get();

        foreach ($skins as $skin) {
            $options[(string) $skin->id] = $skin->market_hash_name . ' - ' . $skin->price;
        }

        return [
            Select::make('skins')
                ->empty('Не выбрано', '0')
                ->fromQuery(
                    Skin::where('market_hash_name', 'LIKE', $this->filters['name'] ?? ''),
                    'market_hash_name'
                )
                ->options($options)
                ->multiple()
        ];
    }
}
