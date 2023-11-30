<?php

namespace Ymsoft\TTLockSdk\Tests\Helper;

use Symfony\Component\Dotenv\Dotenv;

trait UseEnv
{
    public function loadEnv(): void
    {
        (new Dotenv())->usePutenv()->bootEnv(__DIR__ . '/../../.env');
    }
}
