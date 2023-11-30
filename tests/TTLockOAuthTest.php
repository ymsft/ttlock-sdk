<?php

namespace Ymsoft\TTLockSdk\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Ymsoft\TTLockSdk\Exception\TTLockException;
use Ymsoft\TTLockSdk\Tests\Helper\UseEnv;
use Ymsoft\TTLockSdk\TTLock;

class TTLockOAuthTest extends TestCase
{
    use UseEnv;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->loadEnv();
    }

    /**
     * @throws TTLockException
     * @throws ClientExceptionInterface
     */
    public function test_get_and_refresh_access_token(): void
    {
        $service = new TTLock(
            clientId: getenv('CLIENT_ID'),
        );

        $response = $service->oauth()->getAccessToken(
            clientSecret: getenv('CLIENT_SECRET'),
            username: getenv('TTLOCK_USERNAME'),
            password: getenv('TTLOCK_PASSWORD'),
        );

        $this->assertArrayHasKey('access_token', $response);
        $this->assertArrayHasKey('refresh_token', $response);

        $response = $service->oauth()->refreshAccessToken(
            clientSecret: getenv('CLIENT_SECRET'),
            refreshToken: $response['refresh_token'],
        );

        $this->assertArrayHasKey('access_token', $response);
        $this->assertArrayHasKey('refresh_token', $response);
    }
}
