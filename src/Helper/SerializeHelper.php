<?php

namespace Ymsoft\TTLockSdk\Helper;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializeHelper
{
    public static function deserialize(array $data, string $class): mixed
    {
        return self::serializer()->deserialize(json_encode($data), $class, 'json');
    }

    public static function deserializeArray(array $data, string $class): array
    {
        return array_map(function ($item) use ($class) {
            return self::deserialize($item, $class);
        }, $data);
    }

    private static function serializer(): Serializer
    {
        return new Serializer(
            normalizers: [new ObjectNormalizer()],
            encoders: [new JsonEncoder()],
        );
    }
}
