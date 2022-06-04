<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Cases;

use App\Models\Categories;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CaseEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('case.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Название')
                ->placeholder('Название'),

            Input::make('case.price')
                ->type('number')
                ->required()
                ->title('Стоимость')
                ->placeholder('Стоимость'),

            Picture::make('case.img')
                ->required()
                ->title('Картинка')
                ->placeholder('Картинка'),

            Select::make('case.categories.')
                ->fromModel(Categories::class, 'name')
                ->multiple()
                ->title(__('Новая категория'))
                ->help('Specify which groups this account should belong to'),
        ];
    }
}
