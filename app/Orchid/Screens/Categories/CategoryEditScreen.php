<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Categories;

use App\Models\Categories;
use App\Orchid\Layouts\Categories\CategoryEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CategoryEditScreen extends Screen
{
    public Categories $category;

    /**
     * Query data.
     *
     * @param Categories $category
     * @return array
     */
    public function query(Categories $category): iterable
    {
        return [
            'category' => $category
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->category->exists ? 'Редактировать категорию' : 'Создать категорию';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Редактирование категории';
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

            Layout::block(CategoryEditLayout::class)
                ->title(__('Общая информация'))
                ->description(__('Общая инфромация о категории'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),
        ];
    }

    /**
     * @param Categories $category
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Categories $category, Request $request): RedirectResponse
    {
        $data = $request->get('category');
        $category->setAttribute('name', $data['name']);
        $category->setAttribute('img', $data['img']);
        $category->save();

        return redirect()->route('platform.categories');
    }
}
