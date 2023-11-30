<?php

namespace Ymsoft\TTLockSdk\Partial;

use Psr\Http\Client\ClientExceptionInterface;
use Ymsoft\TTLockSdk\Exception\TTLockException;
use Ymsoft\TTLockSdk\Service\Client;

class OAuthPartial
{
    public function __construct(
        private readonly string $clientId,
        private readonly Client $client,
    ) {}

    /**
     * @param string $clientSecret
     * @param string $username
     * @param string $password
     * @return array{
     *     access_token: string,
     *     uid: int,
     *     expires_in: int,
     *     refresh_token: string
     * }
     * @throws ClientExceptionInterface
     * @throws TTLockException
     */
    public function getAccessToken(
        string $clientSecret,
        string $username,
        string $password,
    ): array {
        return $this->client->post(
            uri: 'https://euapi.ttlock.com/oauth2/token',
            body: [
                'clientId' => $this->clientId,
                'clientSecret' => $clientSecret,
                'username' => $username,
                'password' => md5($password),
            ]
        );
    }

    /**
     * @param string $clientSecret
     * @param string $refreshToken
     * @return array{
     *  access_token: string,
     *  expires_in: int,
     *  refresh_token: string,
     * }
     * @throws ClientExceptionInterface
     * @throws TTLockException
     */
    public function refreshAccessToken(
        string $clientSecret,
        string $refreshToken,
    ): array {
        return $this->client->post(
            uri: 'https://euapi.ttlock.com/oauth2/token',
            body: [
                'clientId' => $this->clientId,
                'clientSecret' => $clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]
        );
    }
}
