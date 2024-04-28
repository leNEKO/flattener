<?php

declare(strict_types=1);

namespace Leneko\Flattener;

class Flattener
{
    /**
     * @return mixed
     */
    public static function flatten(mixed $input)
    {
        return \iterator_to_array(
            self::flattenProcess($input),
            true
        );
    }

    /**
     * @return \Generator<mixed>
     */
    public static function flattenProcess(
        mixed $input,
        string $prefix = '',
        array $result = [],
    ): \Generator {
        if (\is_array($input)) {
            $isList = \array_is_list($input);
            /** @var mixed $value */
            foreach ($input as $key => $value) {
                if ($isList) {
                    $template = $prefix ? '[%s]' : '%s';
                } else {
                    $template = '.%s';
                }

                $path = \sprintf(
                    $template,
                    self::escape((string)$key)
                );

                yield from self::flattenProcess(
                    $value,
                    "$prefix$path",
                    $result,
                );
            }
        } else {
            yield $prefix => $input;
        }
    }

    public static function unflatten(
        mixed $input
    ): mixed {
        if (!\is_array($input)) {
            return $input;
        }

        $result = [];

        /** @var mixed $value */
        foreach ($input as $key => $value) {
            $pointer = &$result;
            foreach (self::extractPath(\strval($key)) as $path) {
                $pointer = &$pointer[$path];
            }
            /** @var mixed **/
            $pointer = $value;
        }

        return $result;
    }

    /**
     * @return \Generator<string>
     */
    public static function extractPath(string $key): \Generator
    {
        $escape = false;
        $path = '';

        foreach (\str_split($key) as $char) {
            if ($char === '\\' && $escape === false) {
                $escape = true;
            } else if ($escape === true) {
                $path .= $char;
                $escape = false;
            } else if (\in_array($char, ['[', ']', '.'])) {
                if ($path !== '') {
                    yield $path;
                }
                $path = '';
            } else {
                $path .= $char;
            }
        }

        if ($path !== '') {
            yield $path;
        }
    }

    private static function escape(string $key): string
    {
        return \addcslashes(
            $key,
            '[].\\',
        );
    }
}
