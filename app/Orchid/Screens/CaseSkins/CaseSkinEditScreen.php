<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CaseSkins;

use App\Models\Cases;
use App\Models\Skin;
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
    const SKIN_LIMIT = 500;
    /**
     * @var Cases
     */
    public $case;

    public $skins;

    public $filters;

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


            $skins = Skin::query()
                ->where('market_hash_name', 'LIKE', "%$nameFilter%")
                ->limit(100)
                ->get();
//                ->keyBy('id')
//                ->value('market_hash_name');

            foreach ($skins as $skin) {
//                var_dump($skin->id);
                $options[(string) $skin->id] = $skin->market_hash_name . ' - ' . $skin->price;
            }

//            var_dump($options);
        return [
            'case' => $case,
            'skins' => $options,
            'filters' => $filters,
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
