<?php

namespace Ymsoft\TTLockSdk\Service;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Ymsoft\TTLockSdk\Exception\TTLockException;

class Client
{
    public function __construct(private readonly ClientInterface $client) {}

    /**
     * @throws TTLockException
     * @throws ClientExceptionInterface
     */
    public function get($uri, array $params = []): ?array
    {
        $response = $this->client->sendRequest(
            new Request(
                method: 'GET',
                uri: count($params) > 0 ? $uri . '?' . http_build_query($params) : $uri,
            )
        );


        $responseArray = json_decode(
            $response->getBody()->getContents(),
            true
        );

        $this->processErrors($responseArray);

        return $responseArray;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws TTLockException
     */
    public function post(string $uri, array $body = []): array
    {
        $response = $this->client->sendRequest(
            new Request(
                method: 'POST',
                uri: $uri,
                headers: [
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
                body: http_build_query($body),
            )
        );

        $responseArray = json_decode(
            $response->getBody()->getContents(),
            true
        );

        $this->processErrors($responseArray);

        return $responseArray;
    }

    /**
     * @throws TTLockException
     */
    private function processErrors(array $response): void
    {
        if (array_key_exists('errcode', $response) && $response['errcode'] !== 0) {
            throw new TTLockException(
                message:$response['errmsg'] ?? 'TTLock exception.',
                code: $response['errcode'],
            );
        }
    }
}
