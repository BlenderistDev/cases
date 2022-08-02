<?php

namespace App\Listeners;

use App\Events\PaymentEvent;
use App\Models\PaymentHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PaymentHistoryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PaymentEvent  $event
     * @return void
     */
    public function handle(PaymentEvent $event)
    {
        $paymentEntity = $event->paymentInfoEntity;

        $paymentHistory = new PaymentHistory();
        $paymentHistory->setAttribute('amount', $paymentEntity->getAmount());
        $paymentHistory->setAttribute('source', $paymentEntity->getSource());
        if (!$paymentHistory->save()) {
            throw new \Exception("Ошибка при сохранении истории платежей");
        }
    }
}
