<?php

namespace Ymsoft\TTLockSdk\Entity;

class Lock
{
    public function __construct(
        public readonly int $lockId,
        public readonly string $lockName,
        public readonly string $lockAlias,
        public readonly string $lockMac,
        public readonly int $electricQuantity,
        /**
         * characteristic value. it is used to indicate what kinds of feature do a lock support.
         * https://euopen.ttlock.com/document/doc?urlName=cloud%2Flock%2FfeatureValueEn.html
         */
        public readonly string $featureValue,
        public readonly int $hasGateway,
        public readonly string $lockData,
        public readonly int $groupId,
        public readonly string $groupName,
        public readonly int $date,
    ) {}
}
