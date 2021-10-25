<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Sort;

use LeoVie\PhpGrouper\Model\GroupIdentifiable;

class GroupIdentifiableSorter
{
    private const A_SAME_AS_B = 0;
    private const A_LOWER_THAN_B = -1;
    private const A_GREATER_THAN_B = 1;

    /**
     * @param GroupIdentifiable[] $groupIdentifiables
     *
     * @return GroupIdentifiable[]
     */
    public function sort(array $groupIdentifiables): array
    {
        usort($groupIdentifiables, fn(GroupIdentifiable $a, GroupIdentifiable $b): int => $this->compareIdentities($a, $b));

        return $groupIdentifiables;
    }

    private function compareIdentities(GroupIdentifiable $a, GroupIdentifiable $b): int
    {
        return match (true) {
            $a->groupID() < $b->groupID() => self::A_LOWER_THAN_B,
            $a->groupID() > $b->groupID() => self::A_GREATER_THAN_B,
            default => self::A_SAME_AS_B
        };
    }
}