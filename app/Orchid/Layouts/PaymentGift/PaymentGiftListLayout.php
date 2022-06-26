<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\PaymentGift;

use App\Models\PaymentGift;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PaymentGiftListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'paymentGift';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('min', 'От')
                ->width(100)
                ->render(
                    fn (PaymentGift $item) => $item->min
                ),
            TD::make('max', 'До')
                ->width(100)
                ->render(
                    fn (PaymentGift $item) => $item->max
                ),
            TD::make('active', 'Активность')
                ->width(10)
                ->render(
                    fn (PaymentGift $item) => $item->active
                ),
            TD::make('delete')
                ->render(function (PaymentGift $paymentGift) {
                    return Button::make('delete')
                        ->method('delete', [
                            'id' => $paymentGift->id,
                        ]);
                }),
            TD::make('edit')
                ->render(function (PaymentGift $paymentGift) {
                    return Link::make(__('Edit'))
                        ->route('platform.systems.paymentGift.edit', $paymentGift->id)
                        ->icon('pencil');
                })
        ];
    }
}
