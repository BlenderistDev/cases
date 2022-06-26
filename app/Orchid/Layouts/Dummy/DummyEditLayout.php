<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Dummy;

use App\Models\Categories;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class DummyEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('dummy.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Имя')
                ->placeholder('Имя'),
            Picture::make('dummy.img')
                ->required()
                ->title('Картинка')
                ->placeholder('Картинка'),
            CheckBox::make('dummy.active')
                ->placeholder('Активность')
                ->value(true)
        ];
    }
}
