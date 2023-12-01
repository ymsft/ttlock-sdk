<?php

namespace Ymsoft\TTLockSdk\Entity;

class QrCode
{
    public function __construct(
        public readonly string $lockAlias,
        /**
         * QR code type, 1-period, 2-permanent, 4-cyclic
         */
        public readonly int $type,
        public readonly int $qrCodeNumber,
        /**
         * if not in valid period, "qrCodeContent" will not be returned
         */
        public readonly ?string $qrCodeContent,
        public readonly string $name,
        public readonly int $startDate,
        public readonly int $endDate,
        public readonly array $cyclicConfig,
        /**
         * Status: 1-normal, 2-invalid or expired, 3-pending
         */
        public readonly int $status,
    ) {}
}
