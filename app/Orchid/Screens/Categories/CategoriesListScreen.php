<?php

namespace App\Orchid\Screens\Categories;

use App\Models\Categories;
use App\Orchid\Layouts\Categories\CategoryListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CategoriesListScreen extends Screen
{
    public function query(): iterable
    {
        return [
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
        return 'Категории';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить')
                ->icon('plus')
                ->route('platform.systems.categories.create'),
        ];
    }

    public function delete(Categories $category)
    {
        $category->delete();
        redirect()->route('platform.categories');
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            CategoryListLayout::class,
        ];
    }
}
