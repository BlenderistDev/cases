<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Cases;

use App\Models\Cases;
use App\Models\Categories;
use App\Orchid\Layouts\Cases\CaseEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CaseEditScreen extends Screen
{
    /**
     * @var Cases
     */
    public $case;

    /**
     * Query data.
     *
     * @param Cases $case
     * @return array
     */
    public function query(Cases $case): iterable
    {
        $case->load('categories');
        return [
            'case' => $case,
            'categories' => Categories::all()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->case->exists ? 'Редактировать кейс' : 'Создать кейс';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Редактирование кейса';
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

            Layout::block(CaseEditLayout::class)
                ->title(__('Общая информация'))
                ->description(__('Общая инфромация о кейсе'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Cases $case
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Cases $case, Request $request): RedirectResponse
    {
        $data = $request->get('case');
        $case->setAttribute('name', $data['name']);
        $case->setAttribute('price', $data['price']);
        $case->setAttribute('img', $data['img']);
        $case->categories()->detach();
        $case->categories()->attach($data['categories']);
        $case->save();

        return redirect()->route('platform.cases');
    }
}
