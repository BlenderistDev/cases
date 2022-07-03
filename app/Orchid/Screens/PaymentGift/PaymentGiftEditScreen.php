<?php

declare(strict_types=1);

namespace App\Orchid\Screens\PaymentGift;

use App\Models\Cases;
use App\Models\PaymentGift;
use App\Models\Skin;
use App\Orchid\Layouts\PaymentGift\PaymentGiftEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class PaymentGiftEditScreen extends Screen
{
    /**
     * @var PaymentGift
     */
    public $paymentGift;

    public $skins;

    /**
     * Query data.
     *
     * @param PaymentGift $paymentGift
     * @return array
     */
    public function query(PaymentGift $paymentGift, Request $request): iterable
    {
        $filters = $request->get('filters');

        if (empty($filters['name']) && $paymentGift->exists) {
            $filters['name'] = $paymentGift->skin()->first()->market_hash_name;
        }
        
        $nameFilter = $filters['name'] ?? null;

        $options = [];
        if (!empty($nameFilter)) {
            $skins = Skin::query()
                ->where('market_hash_name', 'LIKE', "%$nameFilter%")
                ->get()
                ->all();

            foreach ($skins as $skin) {
                $options[$skin->id] = $skin->market_hash_name . ' - ' . $skin->price;
            }
        }

        return [
            'paymentGift' => $paymentGift,
            'skins' => $options,
            'filters' => $filters,
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
            Layout::columns(
                [
                    Layout::rows(
                        [
                            Input::make('filters.name')
                                ->title('Имя')
                                ->style('width:200px'),
                        ]
                    )
                ]
            ),
            Layout::rows([
                Group::make(
                    [
                        Button::make('Применить')->method('applyFilters')->type(Color::SECONDARY())->novalidate(),
                        Button::make('Сбросить')->method('resetFilters')->type(Color::ERROR())->tabindex(3),
                    ]
                )->autoWidth(),
            ]),
            Layout::block(new PaymentGiftEditLayout($this->skins))
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
        $paymentGift->setAttribute('skin_id', $data['skin_id']);
        $paymentGift->save();


        return redirect()->route('platform.paymentGift');
    }

    /**
     * @param Request $request
     * @param PaymentGift $paymentGift
     * @return RedirectResponse
     */
    public function applyFilters(Request $request, PaymentGift $paymentGift): RedirectResponse
    {
        $filters = $request->get('filters');

        if ($paymentGift->exists) {
            return redirect()->route(
                'platform.systems.paymentGift.edit',
                [
                    'filters' => $filters,
                    'paymentGift' => $paymentGift->id
                ]);
        } else {
            return redirect()->route(
                'platform.systems.paymentGift.create',
                [
                    'filters' => $filters,
                ]);
        }

    }

    /**
     * @param PaymentGift $paymentGift
     * @return RedirectResponse
     */
    public function resetFilters(PaymentGift $paymentGift): RedirectResponse
    {
        return redirect()->route('platform.systems.paymentGift.edit', $paymentGift->id);
    }
}
