<?php


use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Ymsoft\TTLockSdk\Entity\Lock;
use Ymsoft\TTLockSdk\Exception\TTLockException;
use Ymsoft\TTLockSdk\Tests\Tools\UseAuthWrapper;
use Ymsoft\TTLockSdk\TTLock;

class LockTest extends TestCase
{
    use UseAuthWrapper;

    /**
     * @throws ClientExceptionInterface
     * @throws TTLockException
     * @throws InvalidArgumentException
     */
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->init();
    }

    /**
     * @throws TTLockException
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     */
    public function test_get_lock_list_of_an_account(): void
    {
        $response = $this->wrapper->process(function (TTLock $service, string $accessToken) {
            return $service->lock()
                ->getTheLockListOfAnAccount(
                    accessToken: $accessToken,
                    pageNo: 1,
                    pageSize: 100,
                );
        });

        $this->assertIsArray($response);
        $this->assertIsArray($response['list']);
        $this->assertInstanceOf(Lock::class, $response['list'][0]);
    }
}
