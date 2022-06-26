<?php

declare(strict_types=1);

namespace App\Orchid\Screens\PaymentGift;

use App\Models\PaymentGift;
use App\Orchid\Layouts\PaymentGift\PaymentGiftEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class PaymentGiftEditScreen extends Screen
{
    /**
     * @var PaymentGift
     */
    public $paymentGift;

    /**
     * Query data.
     *
     * @param PaymentGift $paymentGift
     * @return array
     */
    public function query(PaymentGift $paymentGift): iterable
    {
        return [
            'paymentGift' => $paymentGift,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->paymentGift->exists ? 'Редактировать подарок' : 'Создать подарок';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Редактирование подарка';
    }

    /**
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
//            'platform.systems.use',/
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [


            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [

            Layout::block(PaymentGiftEditLayout::class)
                ->title(__('Общая информация'))
                ->description(__('Общая информация о подарке'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),
        ];
    }

    /**
     * @param PaymentGift $paymentGift
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(PaymentGift $paymentGift, Request $request): RedirectResponse
    {
        $data = $request->get('paymentGift');
        $paymentGift->setAttribute('min', $data['min']);
        $paymentGift->setAttribute('max', $data['max']);
        $paymentGift->setAttribute('active', isset($data['active']) ? 1 : 0);
        $paymentGift->save();

        return redirect()->route('platform.paymentGift');
    }
}
