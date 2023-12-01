<?php

namespace Ymsoft\TTLockSdk\Tests\Helper;

use Ymsoft\TTLockSdk\Entity\Lock;
use Ymsoft\TTLockSdk\Helper\DateHelper;
use Ymsoft\TTLockSdk\Helper\SerializeHelper;
use PHPUnit\Framework\TestCase;

class SerializeHelperTest extends TestCase
{
    public function test_serialize()
    {
        $lock = SerializeHelper::deserialize([
            'lockId' => 1,
            'lockName' => 'name',
            'lockAlias' => 'alias',
            'lockMac' => 'mac',
            'electricQuantity' => 50,
            'featureValue' => 'feature',
            'hasGateway' => 0,
            'lockData' => 'data',
            'groupId' => 1,
            'groupName' => 'groupName',
            'date' => DateHelper::now(),
        ], Lock::class);

        $this->assertInstanceOf(Lock::class, $lock);
    }
}
