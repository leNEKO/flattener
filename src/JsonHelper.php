<?php

declare(strict_types=1);

namespace Leneko\Flattener;

final class JsonHelper
{

    public static function load(string $path): mixed
    {
        return \json_decode(
            \file_get_contents($path),
            true
        );
    }

    public static function output(mixed $data): string
    {
        return \json_encode(
            $data,
            JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES,
        );
    }
}
