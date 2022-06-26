<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Loyalty;

use App\Models\Cases;
use App\Models\Categories;
use App\Models\Options;
use App\Orchid\Layouts\Cases\CaseEditLayout;
use App\Orchid\Layouts\Loyalty\LoyaltyEditLayout;
use App\Services\Loyalty\Discounts\NameLoyalty\Entities\NameLoyaltyInfo;
use App\Services\Loyalty\Discounts\NameLoyalty\Repositories\NameLoyaltyRepository;
use App\Services\Loyalty\Discounts\PaymentBonus\Entities\PaymentBonusEntity;
use App\Services\Loyalty\Discounts\PaymentBonus\Repositories\PaymentBonusRepository;
use App\Services\Options\OptionsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class LoyaltyScreen extends Screen
{
    /**
     * @var Cases
     */
    public $case;

    /**
     * Query data.
     *
     * @param PaymentBonusRepository $paymentBonusRepository
     * @param NameLoyaltyRepository $nameLoyaltyRepository
     * @return array
     */
    public function query(PaymentBonusRepository $paymentBonusRepository, NameLoyaltyRepository $nameLoyaltyRepository): iterable
    {
        $paymentBonus = $paymentBonusRepository->getPaymentBonusInfo();
        $nameLoyalty = $nameLoyaltyRepository->getLoyaltyInfo();

        return [
            'paymentBonus.promocode' => $paymentBonus->getPromocode(),
            'paymentBonus.value' => $paymentBonus->getValue(),
            'paymentBonus.currentCount' => $paymentBonus->getCurrentCount(),
            'paymentBonus.maxCount' => $paymentBonus->getMaxCount(),
            'nameLoyalty.pattern' => $nameLoyalty->getPattern(),
            'nameLoyalty.value' => $nameLoyalty->getValue(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Настройка лояльности';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Настройка лояльности';
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

    public function layout(): array
    {
        return [
            Layout::accordion([
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
            ]),
        ];
    }

    /**
     * @param Request $request
     * @param PaymentBonusRepository $paymentBonusRepository
     * @param NameLoyaltyRepository $nameLoyaltyRepository
     * @return RedirectResponse
     */
    public function save(Request $request, PaymentBonusRepository $paymentBonusRepository, NameLoyaltyRepository $nameLoyaltyRepository): RedirectResponse
    {
        $paymentBonusData = $request->get('paymentBonus');
        $paymentBonusRepository->setPaymentBonusInfo(new PaymentBonusEntity(
            $paymentBonusData['promocode'],
            (int) $paymentBonusData['value'],
            (int) $paymentBonusData['currentCount'],
            (int) $paymentBonusData['maxCount']
        ));

        $nameLoyaltyData = $request->get('nameLoyalty');
        $nameLoyaltyRepository->setLoyaltyInfo(new NameLoyaltyInfo((int) $nameLoyaltyData['value'], $nameLoyaltyData['pattern']));

        return redirect()->route('platform.loyalty');
    }
}
