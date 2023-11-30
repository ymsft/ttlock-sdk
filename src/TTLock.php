<?php

namespace Ymsoft\TTLockSdk;

use Psr\Http\Client\ClientInterface;
use Ymsoft\TTLockSdk\Partial\OAuthPartial;
use Ymsoft\TTLockSdk\Partial\QrCodePartial;
use Ymsoft\TTLockSdk\Service\Client;

class TTLock
{
    private Client $client;

    public function __construct(
        private readonly string $clientId,
        ?ClientInterface $client = null,
    ) {
        $this->setClient($client);
    }

    public function oauth(): OAuthPartial
    {
        return new OAuthPartial(
            clientId: $this->clientId,
            client: $this->client,
        );
    }

    public function qrCode(): QrCodePartial
    {
        return new QrCodePartial(
            clientId: $this->clientId,
            client: $this->client,
        );
    }

    private function setClient(?ClientInterface $client): void
    {
        $client = is_null($client)
            ? new \GuzzleHttp\Client()
            : $client;

        $this->client = new Client($client);
    }
}
