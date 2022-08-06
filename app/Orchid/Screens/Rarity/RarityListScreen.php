<?php

namespace App\Orchid\Screens\Rarity;

use App\Models\Rarity;
use App\Models\SkinPrice;
use App\Services\Rarity\Services\RarityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class RarityListScreen extends Screen
{
    public $rarityList;

    public function query(RarityService $rarityService): iterable
    {
        $rarityList = SkinPrice::query()
            ->distinct()
            ->select('ru_rarity')
            ->get()
            ->reduce(function($res, $val) use ($rarityService) {
                if (!empty($val['ru_rarity'])) {
                    $res[$val['ru_rarity']] = $rarityService->getColor($val['ru_rarity']);
                }
                return $res;
            }, []);

        return [
            'rarityList' => $rarityList,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Настройки редкости';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    public function save(Request $request)
    {
        DB::transaction(function () use ($request) {
            Rarity::query()->delete();

            foreach ($request->get('rarityList') as $name => $value) {
                $rarity = new Rarity();
                $rarity->setAttribute('name', $name);
                $rarity->setAttribute('color', $value);
                if (!$rarity->save()) {
                    throw new \Exception('Не удалось сохранить редкость');
                }
            }
        });

    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $fields = [];
        foreach ($this->rarityList as $name => $value) {
            $fields []= Select::make('rarityList.' . $name)
                ->title($name)
                ->options(RarityService::OPTIONS);
        }

        $fields []= Button::make('Сохранить')
            ->method('save')
            ->type(Color::PRIMARY());
        return [
            Layout::rows($fields),
        ];
    }
}
