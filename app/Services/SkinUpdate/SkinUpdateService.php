<?php

declare(strict_types=1);

namespace App\Services\SkinUpdate;

use App\Models\Skin;
use App\Models\SkinPrice;
use App\Services\Market\MarketService;
use Illuminate\Support\Facades\DB;

class SkinUpdateService
{
    public function __construct(
        private MarketService $marketService
    )
    {
    }

    public function updatePrices(): void
    {
        $skins = $this->marketService->rubItemsRequest();

        if (empty($skins)) {
            return;
        }

        DB::transaction(function () use ($skins) {
            SkinPrice::query()->update(['active' => 0]);

            foreach (array_chunk($skins,2000, true) as $items) {
                SkinPrice::upsert(
                    array_map(
                        function ($item, $key) {
                            return array_merge($item,
                            [
                                'active' => 1,
                                'steam_item_id' => explode('_', $key)[0],
                                'steam_item_second_id' => explode('_', $key)[1],
                                'steam_full_id' => $key,
                            ]);
                        },
                        $items,
                        array_keys($items)
                    ),
                    ['steam_full_id'],
                    [
                        'price',
                        'buy_order',
                        'avg_price',
                        'popularity_7d',
                        'market_hash_name',
                        'ru_name',
                        'ru_rarity',
                        'ru_quality',
                        'text_color',
                        'bg_color',
                        'active',
                    ]
                );
            }
        });
    }

    public function updateSkins(): void
    {
        $skinPrices = $this->marketService->rubPriceRequest();

        if (empty($skinPrices)) {
            return;
        }

        DB::transaction(function () use ($skinPrices) {
            Skin::query()->update(['active' => 0]);

            foreach (array_chunk($skinPrices,1000) as $prices) {
                Skin::upsert(
                    array_map(fn(array $price): array => array_merge($price, ['active' => 1]), $prices),
                    ['market_hash_name'],
                    ['price', 'volume', 'active']
                );
            }
        });
    }
}
