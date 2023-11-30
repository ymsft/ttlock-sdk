<?php

namespace Ymsoft\TTLockSdk\Helper;

class DateHelper
{
    public static function now(): int
    {
        return (new \DateTime())->getTimestamp() * 1000;
    }

    public static function tomorrow(): int
    {
        return (new \DateTime())
                ->add(new \DateInterval('P1D'))
                ->getTimestamp() * 1000;
    }
}
