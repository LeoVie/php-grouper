<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Tests\Unit\Service;

use LeoVie\PhpGrouper\Model\GroupIdentifiable;
use LeoVie\PhpGrouper\Service\Grouper;
use LeoVie\PhpGrouper\Sort\GroupIdentifiableSorter;
use LeoVie\PhpGrouper\Tests\TestDouble\Model\GroupIdentifiableDouble;
use PHPUnit\Framework\TestCase;

class GrouperTest extends TestCase
{
    /** @dataProvider groupProvider */
    public function testGroup(array $expected, array $identifiables): void
    {
        $groupIdentifiableSorter = $this->createMock(GroupIdentifiableSorter::class);
        $groupIdentifiableSorter->method('sort')->willReturnArgument(0);

        self::assertSame($expected, (new Grouper($groupIdentifiableSorter))->group($identifiables));
    }

    public function groupProvider(): \Generator
    {
        yield 'empty' => [
            'expected' => [],
            'identifiables' => [],
        ];

        $identifiable = new GroupIdentifiableDouble('abc');
        yield 'only one identifiable' => [
            'expected' => [
                [
                    $identifiable,
                ],
            ],
            'identifiables' => [$identifiable],
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('abc'),
        ];
        yield 'same identifiables' => [
            'expected' => [
                [
                    $identifiables[0],
                    $identifiables[1],
                ],
            ],
            'identifiables' => $identifiables,
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('def')
        ];
        yield 'different identifiables' => [
            'expected' => [
                [
                    $identifiables[0],
                ],
                [
                    $identifiables[1],
                ],
            ],
            'identifiables' => $identifiables,
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('def')
        ];
        yield 'mixed' => [
            'expected' => [
                [
                    $identifiables[0],
                    $identifiables[1],
                ],
                [
                    $identifiables[2],
                ],
            ],
            'identifiables' => $identifiables,
        ];
    }
}