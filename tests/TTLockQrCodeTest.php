<?php

namespace Ymsoft\TTLockSdk\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Ymsoft\TTLockSdk\Exception\TTLockException;
use Ymsoft\TTLockSdk\Helper\DateHelper;
use Ymsoft\TTLockSdk\Tests\Helper\UseAuthWrapper;
use Ymsoft\TTLockSdk\TTLock;

class TTLockQrCodeTest extends TestCase
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
    public function test_process_qr_code(): void
    {
        $response = $this->wrapper->process(function (TTLock $service, string $accessToken) {
            return $service->qrCode()
                ->addQrCode(
                    accessToken: $accessToken,
                    lockId: (int) getenv('TTLOCK_LOCK_ID'),
                    type: 1,
                    startDate: DateHelper::now(),
                    endDate: DateHelper::tomorrow(),
                );
        });

        $this->assertArrayHasKey('qrCodeId', $response);
        $this->assertArrayHasKey('qrCodeNumber', $response);
        $this->assertArrayHasKey('link', $response);

        $qrCodeId = $response['qrCodeId'];

        $qrCodeData = $this->wrapper->process(function (TTLock $service, string $accessToken) use ($qrCodeId) {
            return $service
                ->qrCode()
                ->getQrCodeData(
                    accessToken: $accessToken,
                    qrCodeId: $qrCodeId,
                );
        });

        $this->assertArrayHasKey('qrCodeContent', $qrCodeData);

        $this->wrapper->process(function (TTLock $service, string $accessToken) use ($qrCodeId) {
            $service->qrCode()->deleteQrCode(
                accessToken: $accessToken,
                lockId: (int) getenv('TTLOCK_LOCK_ID'),
                qrCodeId: $qrCodeId
            );
        });

        $this->expectException(TTLockException::class);

        $this->wrapper->process(function (TTLock $service, string $accessToken) use ($qrCodeId) {
            $service
                ->qrCode()
                ->getQrCodeData(
                    accessToken: $accessToken,
                    qrCodeId: $qrCodeId,
                );
        });
    }
}
