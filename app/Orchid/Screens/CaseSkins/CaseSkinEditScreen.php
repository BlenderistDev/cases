<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CaseSkins;

use App\Models\Cases;
use App\Models\Skin;
use App\Models\SkinPrice;
use App\Models\UserSkin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CaseSkinEditScreen extends Screen
{
    private const SKIN_LIMIT = 1000;
    /**
     * @var Cases
     */
    public $case;

    public $skins;

    public $filters;

    public $rarityList;

    /**
     * Query data.
     *
     * @param Cases $case
     * @param Request $request
     * @return array
     */
    public function query(Cases $case, Request $request): iterable
    {
        $filters = $request->get('filters');
        $nameFilter = $filters['name'] ?? null;
        $priceFromFilter = $filters['priceFrom'] ?? null;
        $priceToFilter = $filters['priceTo'] ?? null;
        $rarityFilter = $filters['rarity'] ?? [];

        if ($rarityFilter) {
            $query = Skin::byRarity($rarityFilter);
        } else {
            $query = Skin::query();
        }

        $query
            ->where('market_hash_name', 'LIKE', "%$nameFilter%")
            ->limit(self::SKIN_LIMIT);

        if ($priceFromFilter) {
            $query
                ->where('price', '>=', (int) $priceFromFilter);
        }

        if ($priceToFilter) {
            $query
                ->where('price', '<=', (int) $priceToFilter);
        }


        $skins = $query->get();

        foreach ($skins as $skin) {
            $options[(string) $skin->id] = $skin->market_hash_name . ' - ' . $skin->price;
        }

        $rarityList = SkinPrice::query()
            ->distinct()
            ->select('ru_rarity')
            ->get()
            ->reduce(function($res, $val) {
                $res[$val['ru_rarity']] = $val['ru_rarity'];
                return $res;
            }, []);

        return [
            'case' => $case,
            'skins' => $options ?? [],
            'filters' => $filters,
            'rarityList' => $rarityList
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Добавить скин к кейсу';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Добавление скин к кейсу';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
//            'platform.systems.use',/
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::columns(
                [
                    Layout::rows(
                        [
                            Input::make('filters.name')
                                ->title('Имя')
                                ->style('width:200px'),
                            Input::make('filters.priceFrom')
                                ->type('number')
                                ->title('Цена от')
                                ->style('width:200px'),
                            Input::make('filters.priceTo')
                                ->type('number')
                                ->title('Цена до')
                                ->style('width:200px'),
                            Select::make('filters.rarity')
                                ->title('Редкость')
                                ->multiple()
                                ->options($this->rarityList)
                                ->style('width:200px'),
                        ]
                    )
                ]
            ),
            Layout::rows([
                Group::make(
                    [
                        Button::make('Применить')->method('applyFilters')->type(Color::SECONDARY()),
                        Button::make('Сбросить')->method('resetFilters')->type(Color::ERROR())->tabindex(3),
                    ]
                )->autoWidth(),
            ]),
            Layout::rows([
                Select::make('skins')
                    ->empty('Не выбрано', '0')
                    ->fromQuery(
                        Skin::where('market_hash_name', 'LIKE', $this->filters['name'] ?? ''),
                        'market_hash_name'
                    )
                    ->options($this->skins)
                    ->multiple()
            ]),
        ];
    }

    /**
     * @param Request $request
     * @param Cases $case
     * @return RedirectResponse
     */
    public function applyFilters(Request $request, Cases $case): RedirectResponse
    {
        $filters = $request->get('filters');

        return redirect()->route(
            'platform.systems.case.skins.edit',
            [
                'filters' => $filters,
                'case' => $case->id
            ]);
    }

    /**
     * @param Cases $case
     * @return RedirectResponse
     */
    public function resetFilters(Cases $case): RedirectResponse
    {
        return redirect()->route('platform.systems.case.skins.edit', $case->id);
    }

    /**
     * @param Cases $case
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Cases $case, Request $request): RedirectResponse
    {
        $case->skins()->attach($request->get('skins'), [
            'percent' => 0
        ]);

        return redirect()->route('platform.systems.case.skins.list', $case->id);
    }
}
