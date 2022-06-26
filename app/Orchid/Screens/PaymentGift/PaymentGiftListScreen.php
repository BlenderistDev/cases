<?php

namespace App\Orchid\Screens\PaymentGift;

use App\Models\PaymentGift;
use App\Orchid\Layouts\PaymentGift\PaymentGiftListLayout;
use App\Services\Loyalty\Discounts\PaymentGift\Entities\PaymentGiftInfoEntity;
use App\Services\Loyalty\Discounts\PaymentGift\Repositories\PaymentGiftRepository;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class PaymentGiftListScreen extends Screen
{
    public function query(PaymentGiftRepository $paymentGiftRepository): iterable
    {
        $paymentGiftInfo = $paymentGiftRepository->getPaymentGiftInfo();
        return [
            'paymentGift' => PaymentGift::all(),
            'settings' => [
                'realUserPercent' => $paymentGiftInfo->getRealUserPercent(),
                'hours' => $paymentGiftInfo->getHours(),
            ]
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Подарок при пополнении';
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
                ->route('platform.systems.paymentGift.create'),
        ];
    }

    public function save(Request $request, PaymentGiftRepository $paymentGiftRepository)
    {
        $paymentGiftData = $request->get('settings');

        $paymentGiftRepository->setPaymentGiftInfo(new PaymentGiftInfoEntity(
            (int) $paymentGiftData['realUserPercent'],
            (int) $paymentGiftData['hours'],
        ));
    }

    public function delete(PaymentGift $paymentGift)
    {
        $paymentGift->delete();
        redirect()->route('platform.paymentGift');
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('settings.realUserPercent')
                    ->type('number')
                    ->required()
                    ->max(0)
                    ->max(100)
                    ->title('процент реальных игроков')
                    ->placeholder('процент реальных игроков'),
                Input::make('settings.hours')
                    ->type('number')
                    ->required()
                    ->title('Количество часов для розыгрыша')
                    ->placeholder('Количество часов для розыгрыша'),

                        Button::make('Сохранить')->method('save')->type(Color::PRIMARY()),


            ]),
            PaymentGiftListLayout::class,
        ];
    }
}
