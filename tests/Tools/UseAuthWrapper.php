<?php

namespace Ymsoft\TTLockSdk\Tests\Tools;

use Exception;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Ymsoft\TTLockSdk\AuthWrapper;
use Ymsoft\TTLockSdk\Exception\TTLockException;
use Ymsoft\TTLockSdk\TTLock;

trait UseAuthWrapper
{
    use UseEnv;

    private ?AuthWrapper $wrapper;

    /**
     * @throws ClientExceptionInterface
     * @throws TTLockException
     * @throws InvalidArgumentException
     */
    public function init(): void
    {
        $this->loadEnv();

        $this->wrapper = new AuthWrapper(
            service: new TTLock(
                clientId: getenv('CLIENT_ID'),
            ),
            clientId: getenv('CLIENT_ID'),
            clientSecret: getenv('CLIENT_SECRET'),
            cache: new FilesystemAdapter(
                directory: __DIR__ . '/../../cache'
            ),
        );

        try {
            $this->wrapper->auth(
                username: getenv('TTLOCK_USERNAME'),
                password: getenv('TTLOCK_PASSWORD'),
            );
        } catch (Exception $e) {
            if ($e->getMessage() !== 'You are already logged in.') {
                throw $e;
            }
        }
    }
}
