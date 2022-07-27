<?php

namespace App\Orchid\Screens\FreeCaseSkins;

use App\Models\Cases;
use App\Models\FreeCases;
use App\Orchid\Layouts\CaseSkins\CaseSkinsListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class FreeCaseSkinsListScreen extends Screen
{
    /**
     * @var Cases
     */
    public $case;

    public function query(FreeCases $freeCase): iterable
    {
        $skins = $freeCase
            ->skins()
            ->withPivot('percent')
            ->get()
            ->all();

        return [
            'skins' => $skins,
            'case' => $freeCase
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Скины для кейса';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить')
                ->icon('plus')
                ->route('platform.systems.freeCases.skins.edit', $this->case->id),
        ];
    }

    public function delete(FreeCases $freeCase, Request $request)
    {
        $freeCase->skins()->detach($request->get('id'));

        return redirect()->route('platform.systems.freeCases.skins.list', $freeCase->id);
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            CaseSkinsListLayout::class,
            Layout::rows([
                Group::make(
                    [
                        Button::make('Сохранить')->method('save')->type(Color::PRIMARY()),
                    ]
                )->autoWidth(),
            ]),
        ];
    }

    public function save(FreeCases $freeCase, Request $request)
    {
        $skins = [];
        foreach ($request->toArray() as $key => $value) {
            if (str_starts_with($key, 'skins_')) {
                $id = str_replace('skins_', '', $key);
                $skins[$id] = $value;
            }
        }

        $skinIdList = array_keys($skins);

        if (count($skinIdList) !== count(array_unique($skinIdList))) {
            Alert::error(sprintf('Необходимо удалить повторяющиеся скины'));
        }

        $percentExpectedSum = 100000;
        if (array_sum($skins) === $percentExpectedSum) {
            foreach ($skins as $id => $percent) {
                $freeCase->skins()->updateExistingPivot($id, ['percent' => $percent]);
            }

            return redirect()->route('platform.systems.freeCases.skins.list', $freeCase->id);
        } else {
            Alert::error(sprintf('Необходимо, чтобы сумма процентов была %d, текущая %d', $percentExpectedSum, array_sum($skins)));
        }
    }
}
