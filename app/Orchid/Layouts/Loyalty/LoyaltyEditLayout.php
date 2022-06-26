<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Loyalty;

use App\Models\Categories;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class LoyaltyEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Layout::accordion([
                'Personal Information' => [
                    Layout::rows([
                        Input::make('user.name')
                            ->type('text')
                            ->required()
                            ->title('Name')
                            ->placeholder('Name'),

                        Input::make('user.email')
                            ->type('email')
                            ->required()
                            ->title('Email')
                            ->placeholder('Email'),
                    ]),
                ],
                'Billing Address'      => [
                    Layout::rows([
                        Input::make('address')
                            ->type('text')
                            ->required()
                            ->title('Адрес доставки')
                            ->placeholder('Ул. Ленина дом 14 оф.162'),
                    ]),
                ],
            ]),
        ];

        return [
            Layout::tabs([
                'Промокод с бонусом за пополнение' => [
                    Layout::rows([
                        Input::make('paymentBonus.promocode')
                            ->type('text')
                            ->max(255)
                            ->required()
                            ->title('Промокод для бонуса при пополнении')
                            ->placeholder('Промокод для бонуса при пополнении'),

                        Input::make('paymentBonus.value')
                            ->type('number')
                            ->required()
                            ->title('Размер бонуса при пополнении, в процентах')
                            ->placeholder('Стоимость'),

                        Input::make('paymentBonus.currentCount')
                            ->type('number')
                            ->required()
                            ->title('Текущее количество использований промокода')
                            ->placeholder('Текущее количество использований промокода'),

                        Input::make('paymentBonus.maxCount')
                            ->type('number')
                            ->required()
                            ->title('Максимальное количество использований промокода')
                            ->placeholder('Максимальное количество использований промокода'),

                    ]),
                ],
                'Бонус за ник при пополнении' => [
                    Layout::rows([
                        Input::make('nameLoyalty.pattern')
                            ->type('text')
                            ->max(255)
                            ->required()
                            ->title('Часть ника для бонуса при пополнении')
                            ->placeholder('Промокод для бонуса при пополнении'),

                        Input::make('nameLoyalty.value')
                            ->type('number')
                            ->required()
                            ->title('Размер бонуса при пополнении, в процентах')
                            ->placeholder('Стоимость')
                    ]),
                ],
            ])
        ];
    }
}
