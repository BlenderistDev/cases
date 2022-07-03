<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\CaseSkins;

use App\Models\Categories;
use App\Models\Skin;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CaseSkinEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Select::make('skins')
                ->form('skins')
                ->title(__('Новый скин'))
        ];
    }
}
