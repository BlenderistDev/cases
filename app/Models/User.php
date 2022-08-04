<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
        'steamid',
        'avatar',
        'balance',
        'steam_trade_link'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
        'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'permissions',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'updated_at',
        'created_at',
    ];

    protected $appends = [
        'showBalance',
        'lastPaymentSum',
    ];

    public function skins(): HasMany
    {
        return $this->hasMany(UserSkin::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function getShowBalanceAttribute(): string
    {
        return sprintf("%01.2f", $this->balance / 100);
    }

    public function getLastPaymentSumAttribute(): int
    {
        $amount = 0;
        foreach ($this->payments()->whereDate('created_at', '>', Carbon::now()->subDay())->get('amount') as $payment) {
            $amount += $payment->amount;
        }
        return floor($amount / 100);
    }
}
