<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Dummy;

use App\Models\Dummy;
use App\Orchid\Layouts\Dummy\DummyEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class DummyEditScreen extends Screen
{
    /**
     * @var Dummy
     */
    public $dummy;

    /**
     * Query data.
     *
     * @param Dummy $dummy
     * @return array
     */
    public function query(Dummy $dummy): iterable
    {
        return [
            'dummy' => $dummy,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->dummy->exists ? 'Редактировать фейкового пользователя' : 'Создать фейкового пользователя';
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

            Layout::block(DummyEditLayout::class)
                ->title(__('Общая информация'))
                ->description(__('Общая инфромация о фейковом пользователе'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Dummy $dummy
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Dummy $dummy, Request $request): RedirectResponse
    {
        $data = $request->get('dummy');
        $dummy->setAttribute('name', $data['name']);
        $dummy->setAttribute('img', $data['img']);
        $dummy->setAttribute('active', isset($data['active']) ? 1 : 0);
        $dummy->save();

        return redirect()->route('platform.dummy');
    }
}
