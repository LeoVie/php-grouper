<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Tests\Functional;

use LeoVie\PhpGrouper\Service\Grouper;
use PHPUnit\Framework\TestCase;

class FrameworkTest extends TestCase
{
    public function testServiceWiring(): void
    {
        $kernel = new TestingKernel('test', true);
        $kernel->boot();
        $grouper = $kernel->getContainer()->get(Grouper::class);

        self::assertInstanceOf(Grouper::class, $grouper);
    }
}