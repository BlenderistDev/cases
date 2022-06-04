<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Categories;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Layouts\Rows;

class CategoryEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('category.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Название')
                ->placeholder('Название'),

            Picture::make('category.img')
                ->required()
                ->title('Картинка')
                ->placeholder('Картинка'),
        ];
    }
}
