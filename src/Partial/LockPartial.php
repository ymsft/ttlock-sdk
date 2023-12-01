<?php

namespace Ymsoft\TTLockSdk\Partial;

use Psr\Http\Client\ClientExceptionInterface;
use Ymsoft\TTLockSdk\Entity\Lock;
use Ymsoft\TTLockSdk\Exception\TTLockException;
use Ymsoft\TTLockSdk\Helper\DateHelper;
use Ymsoft\TTLockSdk\Helper\SerializeHelper;
use Ymsoft\TTLockSdk\Service\Client;

class LockPartial
{
    public function __construct(
        private readonly string $clientId,
        private readonly Client $client
    ) {}

    /**
     * @throws TTLockException
     * @throws ClientExceptionInterface
     * @return array{
     *     list: Lock[],
     *     pageNo: int,
     *     pageSize: int,
     *     pages: int,
     *     total: int
     * }
     */
    public function getTheLockListOfAnAccount(
        string $accessToken,
        int $pageNo,
        int $pageSize,
        string $lockAlias = null,
        int $groupId = null
    ): array {
        $payload = [
            'clientId' => $this->clientId,
            'accessToken' => $accessToken,
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
            'date' => DateHelper::now(),
        ];

        if ($lockAlias) {
            $payload['lockAlias'] = $lockAlias;
        }

        if ($groupId) {
            $payload['groupId'] = $groupId;
        }

        $response = $this->client->post(
            uri: 'https://euapi.ttlock.com/v3/lock/list',
            body: $payload,
        );

        $response['list'] = SerializeHelper::deserializeArray($response['list'], Lock::class);

        return $response;
    }
}
