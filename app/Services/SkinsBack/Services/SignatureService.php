<?php

declare(strict_types=1);

namespace App\Services\SkinsBack\Services;

use Exception;
use Illuminate\Http\Request;

class SignatureService
{
    public function buildSignature(array $params)
    {
        $secretKey = $this->getSecretKey();

        ksort($params);

        $paramsString = '';

        foreach($params as $key => $value)
        {
            if ($key == 'sign') {
                continue;
            }

            if(is_array($value)) {
                continue;

            }

            $paramsString .= $key .':'. $value .';';
        }

        return hash_hmac('sha1', $paramsString, $secretKey);
    }

    public function checkSignature(Request $request)
    {
        $requestSign = $request->get('sign');
        $sign = $this->buildSignature($request->toArray());

        if ($requestSign !== $sign) {
            throw new Exception('invalid sign');
        }
    }

    /**
     * @return string
     */
    private function getSecretKey(): string
    {
        return getenv('SKINSBACK_SECRET_KEY') ?? '';
    }
}
