<?php

declare(strict_types=1);

use App\Orchid\Screens\Cases\CaseEditScreen;
use App\Orchid\Screens\Cases\CasesListScreen;
use App\Orchid\Screens\CaseSkins\CaseSkinEditScreen;
use App\Orchid\Screens\CaseSkins\CaseSkinsListScreen;
use App\Orchid\Screens\Categories\CategoryEditScreen;
use App\Orchid\Screens\Categories\CategoriesListScreen;
use App\Orchid\Screens\Dummy\DummyEditScreen;
use App\Orchid\Screens\Dummy\DummyListScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\FreeCases\FreeCaseEditScreen;
use App\Orchid\Screens\FreeCases\FreeCasesListScreen;
use App\Orchid\Screens\FreeCaseSkins\FreeCaseSkinEditScreen;
use App\Orchid\Screens\FreeCaseSkins\FreeCaseSkinsListScreen;
use App\Orchid\Screens\Loyalty\LoyaltyScreen;
use App\Orchid\Screens\PaymentGift\PaymentGiftEditScreen;
use App\Orchid\Screens\PaymentGift\PaymentGiftListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

Route::screen('cases', CasesListScreen::class)
    ->name('platform.cases');

Route::screen('cases/create', CaseEditScreen::class)
    ->name('platform.systems.cases.create');

Route::screen('cases/{case}/edit', CaseEditScreen::class)
    ->name('platform.systems.cases.edit');

Route::screen('cases/{case}/skins/list', CaseSkinsListScreen::class)
    ->name('platform.systems.cases.skins.list');

Route::screen('cases/{case}/skins/edit', CaseSkinEditScreen::class)
    ->name('platform.systems.cases.skins.edit');

Route::screen('freeCases', FreeCasesListScreen::class)
    ->name('platform.freeCases');

Route::screen('freeCases/create', FreeCaseEditScreen::class)
    ->name('platform.systems.freeCases.create');

Route::screen('freeCases/{freeCase}/edit', FreeCaseEditScreen::class)
    ->name('platform.systems.freeCases.edit');

Route::screen('freeCases/{freeCase}/skins/list', FreeCaseSkinsListScreen::class)
    ->name('platform.systems.freeCases.skins.list');

Route::screen('freeCases/{case}/skins/edit', FreeCaseSkinEditScreen::class)
    ->name('platform.systems.freeCases.skins.edit');

Route::screen('categories', CategoriesListScreen::class)
    ->name('platform.categories');

Route::screen('categories/create', CategoryEditScreen::class)
    ->name('platform.systems.categories.create');

Route::screen('categories/{category}/edit', CategoryEditScreen::class)
    ->name('platform.systems.categories.edit');

Route::screen('dummy', DummyListScreen::class)
    ->name('platform.dummy');

Route::screen('dummy/create', DummyEditScreen::class)
    ->name('platform.systems.dummy.create');

Route::screen('dummy/{dummy}/edit', DummyEditScreen::class)
    ->name('platform.systems.dummy.edit');

Route::screen('paymentGift', PaymentGiftListScreen::class)
    ->name('platform.paymentGift');

Route::screen('paymentGift/create', PaymentGiftEditScreen::class)
    ->name('platform.systems.paymentGift.create');

Route::screen('liveWinners', \App\Orchid\Screens\LiveWinners\LiveWinnersScreen::class)
    ->name('platform.liveWinners');

Route::screen('paymentGift/{paymentGift}/edit', PaymentGiftEditScreen::class)
    ->name('platform.systems.paymentGift.edit');


Route::screen('loyalty', LoyaltyScreen::class)
    ->name('platform.loyalty');



// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('User'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push('Example screen');
    });

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');
