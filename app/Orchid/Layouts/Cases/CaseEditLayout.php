<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Cases;

use App\Models\Categories;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CaseEditLayout extends Rows
{
    public function __construct(private bool $isShowCategories)
    {
    }

    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        $fields = [
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
            Cropper::make('case.img')
                ->width(500)
                ->height(500),
        ];

        if ($this->isShowCategories) {
            $fields[] = Select::make('case.categories.')
                ->fromModel(Categories::class, 'name')
                ->multiple()
                ->title(__('Новая категория'))
                ->help('Specify which groups this account should belong to');
        }

        return $fields;
    }
}
