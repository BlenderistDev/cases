<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make('Кейсы')
                ->icon('monitor')
                ->slug('cases')
                ->list(
                    [
                        Menu::make('Кейсы')
                            ->icon('monitor')
                            ->route('platform.cases'),
                        Menu::make('Бесплатные кейсы')
                            ->icon('monitor')
                            ->route('platform.freeCases'),
                    ]
                ),
            Menu::make('Категории')
                ->icon('monitor')
                ->route('platform.categories'),
            Menu::make('Лояльность')
                ->icon('monitor')
                ->slug('loyalty')
                ->list(
                    [
                        Menu::make('Бонусы')
                            ->icon('monitor')
                            ->route('platform.loyalty'),
                        Menu::make('Подарок за пополнение')
                            ->icon('monitor')
                            ->route('platform.paymentGift'),
                        Menu::make('Фейковые пользователи')
                            ->icon('monitor')
                            ->route('platform.dummy'),
                        Menu::make('Лайв лента')
                            ->icon('monitor')
                            ->route('platform.liveWinners'),
                        Menu::make('Статистика кейсов')
                            ->icon('monitor')
                            ->route('platform.caseStatistics'),
                    ]
                ),
            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
