<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CaseSkins;

use App\Models\Cases;
use App\Orchid\Layouts\CaseSkins\CaseSkinFilterLayout;
use App\Orchid\Layouts\CaseSkins\CaseSkinSelectLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CaseSkinEditScreen extends Screen
{
    /**
     * @var Cases
     */
    public $case;

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

        return [
            'case' => $case,
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
            Layout::columns([CaseSkinFilterLayout::class]),
            Layout::rows([
                Group::make(
                    [
                        Button::make('Применить')->method('applyFilters')->type(Color::SECONDARY()),
                        Button::make('Сбросить')->method('resetFilters')->type(Color::ERROR())->tabindex(3),
                    ]
                )->autoWidth(),
            ]),
            Layout::columns([new CaseSkinSelectLayout(
                (string) ($this->filters['name'] ?? ''),
                (int) ($this->filters['priceFrom'] ?? 0),
                (int) ($this->filters['priceTo'] ?? 0),
                $this->filters['rarity'] ?? []
            )]),
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
            'platform.systems.cases.skins.edit',
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
        return redirect()->route('platform.systems.cases.skins.edit', $case->id);
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

        return redirect()->route('platform.systems.cases.skins.list', $case->id);
    }
}
