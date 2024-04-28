<?php

declare(strict_types=1);

namespace Leneko\Flattener;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


#[CoversClass(Flattener::class)]
#[CoversClass(JsonHelper::class)]
#[CoversClass(JsonFlatten::class)]
class FlattenerTest extends TestCase
{
    /**
     * @return \Generator<list{string}>
     */
    public static function jsonPathProvider(): \Generator
    {
        foreach (glob('tests/data/*.json') as $path) {
            yield [$path];
        }
        foreach (glob('tests/data/JSONPath/*.json') as $path) {
            yield [$path];
        }
    }

    #[DataProvider('jsonPathProvider')]
    public function testJsonUnflattenFlatten(string $path): void
    {
        $expected = JsonHelper::output(
            JsonHelper::load($path)
        );
        $flattened = JsonFlatten::flatten($expected);
        $actual = JsonFlatten::unflatten($flattened);

        static::assertEquals($expected, $actual);
    }

    /**
     * @return \Generator<list{mixed}>
     */
    public static function unflattenProvider(): \Generator
    {
        yield ["A string"];
        yield [true];
        yield [false];
        yield [-12345];
        yield [1.2345];
    }

    #[DataProvider('unflattenProvider')]
    public function testUnflattenScalar(mixed $input): void
    {
        $actual = Flattener::unflatten($input);

        static::assertEquals($input, $actual);
    }

    public function testExtractPath(): void
    {
        $input = "ceci\.cela.machin.patin";

        $expected = [
            'ceci.cela',
            'machin',
            'patin',
        ];

        static::assertEquals(
            $expected,
            \iterator_to_array(
                Flattener::extractPath($input)
            )
        );
    }
}
