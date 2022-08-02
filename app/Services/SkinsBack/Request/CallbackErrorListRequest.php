<?php

declare(strict_types=1);

namespace App\Services\SkinsBack\Request;

use App\Services\SkinsBack\Entities\CallbackErrorEntity;
use App\Services\SkinsBack\Logger\Logger;
use App\Services\SkinsBack\Services\SignatureService;
use Illuminate\Support\Facades\Http;

class CallbackErrorListRequest
{
    private const URL = 'https://skinsback.com/api.php';

    private const METHOD = 'callback_error_list';

    public function __construct(private SignatureService $signatureService, private Logger $logger)
    {
    }

    /**
     * @return CallbackErrorEntity[]
     * @throws \Exception
     */
    public function execute(): array
    {
        $params = [
            'shopid' => getenv('SKINSBACK_CLIENT_ID'),
        ];

        $params['sign'] = $this->signatureService->buildSignature($params);

        $response = Http::post(self::URL, $params);

        $this->logger->log(null, $params, self::METHOD, $response);

        if ($response->status() !== 200) {
            throw new \Exception('Ошибка при получении списка недошедших callback');
        }

        return array_map(
            fn($item) => new CallbackErrorEntity(
                (string) $item['transaction_id'],
                (string) $item['order_id'],
                (int) $item['http_code']
            ),
            $response->json('items', [])
        );
    }
}
