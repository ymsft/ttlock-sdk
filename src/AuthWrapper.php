<?php

namespace Ymsoft\TTLockSdk;

use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Ymsoft\TTLockSdk\Exception\TTLockException;

class AuthWrapper
{
    public const CACHE_KEY = 'TTLOCK_AUTH_CREDENTIALS';

    public function __construct(
        private readonly TTLock $service,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly CacheItemPoolInterface $cache
    ) {}

    /**
     * @throws InvalidArgumentException
     * @throws TTLockException|ClientExceptionInterface
     * @throws Exception
     */
    public function auth(
        string $username,
        string $password,
    ): void {
        if ($this->isAuth()) {
            throw new Exception('You are already logged in.');
        }

        $response = $this->service->oauth()->getAccessToken(
            clientSecret: $this->clientSecret,
            username: $username,
            password: $password,
        );

        $this->setCredentials(
            accessToken: $response['access_token'],
            refreshToken: $response['refresh_token'],
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function logout(): void
    {
        $this->cache->deleteItem($this->getCacheKey());
    }

    /**
     * @throws InvalidArgumentException
     * @throws TTLockException|ClientExceptionInterface
     */
    public function process($callback): mixed
    {
        $credentials = $this->getCredentials();

        try {
            return call_user_func($callback, $this->service, $credentials['access_token']);
        } catch (TTLockException $exception) {
            if ($exception->getCode() === 10003) {
                $response = $this->refreshToken();

                return call_user_func($callback, $this->service, $response['access_token']);
            }

            throw $exception;
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function isAuth(): bool
    {
        return $this->cache->getItem($this->getCacheKey())->isHit();
    }

    /**
     * @throws ClientExceptionInterface
     * @throws TTLockException
     * @throws InvalidArgumentException
     */
    private function refreshToken(): array
    {
        $credentials = $this->getCredentials();

        $response = $this->service->oauth()->refreshAccessToken(
            clientSecret: $this->clientSecret,
            refreshToken: $credentials['refresh_token'],
        );

        $this->setCredentials(
            accessToken: $response['access_token'],
            refreshToken: $response['refresh_token'],
        );

        return $response;
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     * @return array{
     *     access_token: string,
     *     refresh_token: string,
     * }
     */
    private function getCredentials(): array
    {
        $item = $this->cache->getItem($this->getCacheKey());

        if (!$item->isHit()) {
            throw new Exception('You are not logged in yet.');
        }

        return json_decode($item->get(), true);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function setCredentials(
        string $accessToken,
        string $refreshToken,
    ): void {
        $item = $this->cache->getItem($this->getCacheKey());

        $item->set(json_encode([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ]));

        $this->cache->save($item);
    }

    private function getCacheKey(): string
    {
        return self::CACHE_KEY . '.' . $this->clientId;
    }
}
