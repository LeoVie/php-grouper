<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Tests\Unit\Sort;

use LeoVie\PhpGrouper\Sort\GroupIdentifiableSorter;
use LeoVie\PhpGrouper\Tests\TestDouble\Model\GroupIdentifiableDouble;
use PHPUnit\Framework\TestCase;

class GroupIdentifiableSorterTest extends TestCase
{
    /** @dataProvider sortProvider */
    public function testSort(array $expected, array $identifiables): void
    {
        self::assertSame($expected, (new GroupIdentifiableSorter())->sort($identifiables));
    }

    public function sortProvider(): \Generator
    {
        yield 'empty' => [
            'expected' => [],
            'identifiables' => [],
        ];

        $identifiable = new GroupIdentifiableDouble('abc');
        yield 'only one identifiable' => [
            'expected' => [$identifiable],
            'identifiables' => [$identifiable],
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('def'),
        ];
        yield 'two already sorted identifiables' => [
            'expected' => $identifiables,
            'identifiables' => $identifiables,
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('abc'),
        ];
        yield 'two same identifiables' => [
            'expected' => $identifiables,
            'identifiables' => $identifiables,
        ];

        $identifiables = [
            new GroupIdentifiableDouble('def'),
            new GroupIdentifiableDouble('abc'),
        ];
        yield 'two unsorted identifiables' => [
            'expected' => [$identifiables[1], $identifiables[0]],
            'identifiables' => $identifiables,
        ];

        $identifiables = [
            new GroupIdentifiableDouble('def'),
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('yxz'),
            new GroupIdentifiableDouble('hij'),
            new GroupIdentifiableDouble('qrs'),
            new GroupIdentifiableDouble('abd'),
        ];
        yield 'multiple unsorted identifiables' => [
            'expected' => [
                $identifiables[1],
                $identifiables[5],
                $identifiables[0],
                $identifiables[3],
                $identifiables[4],
                $identifiables[2],
            ],
            'identifiables' => $identifiables,
        ];
    }
}