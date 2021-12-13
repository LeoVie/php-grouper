<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Tests\TestDouble\Strategy;

use LeoVie\PhpGrouper\Model\GroupIdentifiable;
use LeoVie\PhpGrouper\Strategy\GroupStrategy;

class GroupStrategyImplementation implements GroupStrategy
{
    public function groupCriterion(GroupIdentifiable $a, GroupIdentifiable $b): bool
    {
        return $a !== $b && $a->groupID() === $b->groupID();
    }
}