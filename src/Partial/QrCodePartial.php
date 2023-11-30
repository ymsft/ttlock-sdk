<?php

namespace Ymsoft\TTLockSdk\Partial;

use Psr\Http\Client\ClientExceptionInterface;
use Ymsoft\TTLockSdk\Exception\TTLockException;
use Ymsoft\TTLockSdk\Helper\DateHelper;
use Ymsoft\TTLockSdk\Service\Client;

class QrCodePartial
{
    public function __construct(
        private readonly string $clientId,
        private readonly Client $client
    ) {}

    /**
     * @throws TTLockException
     * @throws ClientExceptionInterface
     * @return array{
     *     qrCodeId: int,
     *     qrCodeNumber: int,
     *     link: string
     * }
     */
    public function addQrCode(
        string $accessToken,
        int $lockId,
        int $type,
        ?string $name = null,
        ?int $startDate = null,
        ?int $endDate = null,
        ?array $cyclicConfig = null,
    ): array {
        $body = [
            'clientId' => $this->clientId,
            'accessToken' => $accessToken,
            'lockId' => $lockId,
            'type' => $type,
            'date' => DateHelper::now(),
        ];

        if ($name) {
            $body['name'] = $name;
        }

        if ($startDate) {
            $body['startDate'] = $startDate;
        }

        if ($startDate) {
            $body['endDate'] = $endDate;
        }

        if ($cyclicConfig) {
            $body['cyclicConfig'] = $cyclicConfig;
        }

        return $this->client->post(
            uri: 'https://euapi.ttlock.com/v3/qrCode/add',
            body: $body,
        );
    }

    /**
     * @throws TTLockException
     * @throws ClientExceptionInterface
     * @return array{
     *     lockAlias: string,
     *     type: int,
     *     qrCodeNumber: int,
     *     qrCodeContent: string,
     *     name: string,
     *     startDate: int,
     *     endDate: int,
     *     cyclicConfig: array,
     *     status: int
     * }
     */
    public function getQrCodeData(
        string $accessToken,
        int $qrCodeId,
    ): array {
        return $this->client->get(
            uri: 'https://euapi.ttlock.com/v3/qrCode/getData',
            params: [
                'clientId' => $this->clientId,
                'accessToken' => $accessToken,
                'qrCodeId' => $qrCodeId,
                'date' => DateHelper::now(),
            ]
        );
    }

    /**
     * @throws ClientExceptionInterface
     * @throws TTLockException
     */
    public function deleteQrCode(
        string $accessToken,
        int $lockId,
        int $qrCodeId,
    ): void {
        $this->client->post(
            uri: 'https://euapi.ttlock.com/v3/qrCode/delete',
            body: [
                'clientId' => $this->clientId,
                'accessToken' => $accessToken,
                'lockId' => $lockId,
                'qrCodeId' => $qrCodeId,
                'date' => DateHelper::now(),
            ]
        );
    }
}
