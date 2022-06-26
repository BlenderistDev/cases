<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\PaymentGift;

use App\Models\Categories;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class PaymentGiftEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('paymentGift.min')
                ->type('number')
                ->required()
                ->title('От')
                ->placeholder('От'),
            Input::make('paymentGift.max')
                ->type('number')
                ->required()
                ->title('До')
                ->placeholder('До'),
            CheckBox::make('paymentGift.active')
                ->placeholder('Активность')
                ->value(1)
        ];
    }
}
