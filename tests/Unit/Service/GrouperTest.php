<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Tests\Unit\Service;

use Generator;
use LeoVie\PhpGrouper\Model\GroupIdentifiable;
use LeoVie\PhpGrouper\Service\Grouper;
use LeoVie\PhpGrouper\Tests\TestDouble\Model\GroupIdentifiableDouble;
use PHPUnit\Framework\TestCase;

class GrouperTest extends TestCase
{
    /** @dataProvider groupByGroupIDProvider */
    public function testGroupByGroupID(array $expected, array $identifiables): void
    {
        $grouped = (new Grouper())->groupByGroupID($identifiables);

        self::assertSame($expected, $grouped);
    }

    public function groupByGroupIDProvider(): Generator
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
            new GroupIdentifiableDouble('def'),
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
            new GroupIdentifiableDouble('def'),
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

    /** @dataProvider groupByCallbackProvider */
    public function testGroupByCallback(array $expected, array $groupIdentifiables, callable $callback): void
    {
        self::assertSame($expected, (new Grouper())->groupByCallback($groupIdentifiables, $callback));
    }

    public function groupByCallbackProvider(): Generator
    {
        yield 'empty' => [
            'expected' => [],
            'identifiables' => [],
            'callback' => fn(GroupIdentifiable $a, GroupIdentifiable $b): bool => $a->groupID() === $b->groupID(),
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('abc'),
        ];
        yield 'same identifiables' => [
            'expected' => [
                'abc' => [
                    $identifiables[0],
                    $identifiables[1],
                    $identifiables[2],
                ],
            ],
            'identifiables' => $identifiables,
            'callback' => fn(GroupIdentifiable $a, GroupIdentifiable $b): bool => $a !== $b && $a->groupID() === $b->groupID(),
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('def'),
            new GroupIdentifiableDouble('ghi'),
        ];
        yield 'different identifiables' => [
            'expected' => [
                'abc' => [$identifiables[0]],
                'def' => [$identifiables[1]],
                'ghi' => [$identifiables[2]],
            ],
            'identifiables' => $identifiables,
            'callback' => fn(GroupIdentifiable $a, GroupIdentifiable $b): bool => $a !== $b && $a->groupID() === $b->groupID(),
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('def'),
            new GroupIdentifiableDouble('abc'),
        ];
        yield 'mixed same and different identifiables' => [
            'expected' => [
                'abc' => [
                    $identifiables[0],
                    $identifiables[2],
                ],
                'def' => [$identifiables[1]],
            ],
            'identifiables' => $identifiables,
            'callback' => fn(GroupIdentifiable $a, GroupIdentifiable $b): bool => $a !== $b && $a->groupID() === $b->groupID(),
        ];

        $identifiables = [
            new GroupIdentifiableDouble('abc'),
            new GroupIdentifiableDouble('abcd'),
            new GroupIdentifiableDouble('def'),
        ];
        yield 'other callback function' => [
            'expected' => [
                'abc' => [
                    $identifiables[0],
                    $identifiables[2],
                ],
                'abcd' => [$identifiables[1]],
                'def' => [
                    $identifiables[2],
                    $identifiables[0],
                ],
            ],
            'identifiables' => $identifiables,
            'callback' => fn(GroupIdentifiable $a, GroupIdentifiable $b): bool => $a !== $b && strlen($a->groupID()) === strlen($b->groupID()),
        ];
    }
}