<?php

declare(strict_types=1);

namespace App\Orchid\Screens\FreeCases;

use App\Models\FreeCases;
use App\Orchid\Layouts\Cases\CaseEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class FreeCaseEditScreen extends Screen
{
    /**
     * @var FreeCases
     */
    public $case;

    /**
     * Query data.
     *
     * @param FreeCases $freeCases
     * @return array
     */
    public function query(FreeCases $freeCases): iterable
    {
        return [
            'case' => $freeCases,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->case->exists ? 'Редактировать бесплатный кейс' : 'Создать бесплатный кейс';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Редактирование бесплатного кейса';
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

            Layout::block(new CaseEditLayout(false))
                ->title(__('Общая информация'))
                ->description(__('Общая инфромация о бесплатном кейсе'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),
        ];
    }

    /**
     * @param FreeCases $freeCases
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(FreeCases $freeCases, Request $request): RedirectResponse
    {
        $data = $request->get('case');
        $freeCases->setAttribute('name', $data['name']);
        $freeCases->setAttribute('price', $data['price']);
        $freeCases->setAttribute('img', $data['img']);
        $freeCases->save();

        return redirect()->route('platform.freeCases');
    }
}
