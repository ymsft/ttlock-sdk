<?php

namespace Ymsoft\TTLockSdk\Tests;

use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Ymsoft\TTLockSdk\AuthWrapper;
use PHPUnit\Framework\TestCase;
use Ymsoft\TTLockSdk\Exception\TTLockException;
use Ymsoft\TTLockSdk\Tests\Helper\UseEnv;
use Ymsoft\TTLockSdk\TTLock;

class AuthWrapperTest extends TestCase
{
    use UseEnv;

    private AuthWrapper $wrapper;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->loadEnv();

        $this->wrapper = new AuthWrapper(
            service: new TTLock(
                clientId: getenv('CLIENT_ID'),
            ),
            clientId: getenv('CLIENT_ID'),
            clientSecret: getenv('CLIENT_SECRET'),
            cache: new FilesystemAdapter(
                directory: __DIR__ . '/../cache/test'
            ),
        );

        $this->wrapper->logout();
    }

    /**
     * @throws ClientExceptionInterface
     * @throws TTLockException
     * @throws InvalidArgumentException
     */
    public function test_auth_and_logout(): void
    {
        $this->wrapper->auth(
            username: getenv('TTLOCK_USERNAME'),
            password: getenv('TTLOCK_PASSWORD'),
        );

        $this->assertTrue($this->wrapper->isAuth());

        $this->wrapper->logout();

        $this->assertFalse($this->wrapper->isAuth());
    }
}
