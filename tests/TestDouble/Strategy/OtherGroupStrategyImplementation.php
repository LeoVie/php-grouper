<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Tests\TestDouble\Strategy;

use LeoVie\PhpGrouper\Model\GroupIdentifiable;
use LeoVie\PhpGrouper\Strategy\GroupStrategy;

class OtherGroupStrategyImplementation implements GroupStrategy
{
    public function groupCriterion(GroupIdentifiable $a, GroupIdentifiable $b): bool
    {
        return $a !== $b && strlen($a->groupID()) === strlen($b->groupID());
    }
}