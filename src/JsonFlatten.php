<?php

declare(strict_types=1);

namespace Leneko\Flattener;

final class JsonFlatten
{
    public static function flatten(string $data): string
    {
        return JsonHelper::output(
            Flattener::flatten(
                \json_decode($data, true)
            )
        );
    }

    public static function unflatten(string $data): string
    {
        return JsonHelper::output(
            Flattener::unflatten(
                \json_decode($data, true)
            )
        );
    }
}
