<?php

namespace App\Orchid\Screens\CaseSkins;

use App\Models\Cases;
use App\Orchid\Layouts\CaseSkins\CaseSkinsListLayout;
use App\Services\Cases\Services\OpenCaseService;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class CaseSkinsListScreen extends Screen
{
    /**
     * @var Cases
     */
    public $case;

    public $profitability;

    public function query(Cases $case, OpenCaseService $openCaseService): iterable
    {
        $skins = $case
            ->skins()
            ->withPivot('percent')
            ->get()
            ->all();

        $profitability = $openCaseService->testCaseOpen($case);

        return [
            'skins' => $skins,
            'case' => $case,
            'profitability' => $profitability
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
                ->route('platform.systems.cases.skins.edit', $this->case->id),
        ];
    }

    public function delete(Cases $case, Request $request)
    {
        $case->skins()->detach($request->get('id'));

        return redirect()->route('platform.systems.cases.skins.list', $case->id);
    }

    /**
     * Views.
     *
     * @return iterable
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Label::make('Выгодность')
                    ->title('Выгодность: ' . $this->profitability),
            ]),
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

    public function save(Cases $case, Request $request)
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
                $case->skins()->updateExistingPivot($id, ['percent' => $percent]);
            }

            return redirect()->route('platform.systems.cases.skins.list', $case->id);
        } else {
            Alert::error(sprintf('Необходимо, чтобы сумма процентов была %d, текущая %d', $percentExpectedSum, array_sum($skins)));
        }
    }
}
