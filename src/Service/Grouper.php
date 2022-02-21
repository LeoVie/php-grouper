<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Service;

use LeoVie\PhpGrouper\Model\GroupIdentifiable;

class Grouper
{
    /**
     * @param GroupIdentifiable[] $groupIdentifiables
     *
     * @return array<GroupIdentifiable[]>
     */
    public function groupByGroupID(array $groupIdentifiables): array
    {
        return array_values(
            $this->groupByCallback(
                $groupIdentifiables,
                fn(GroupIdentifiable $a, GroupIdentifiable $b): bool => $a !== $b && $a->groupID() === $b->groupID()
            )
        );
    }

    /**
     * @param iterable<GroupIdentifiable> $groupIdentifiables
     *
     * @return array<GroupIdentifiable[]>
     */
    public function groupByCallback(iterable $groupIdentifiables, callable $callback): array
    {
        $grouped = [];

        foreach ($groupIdentifiables as $a) {
            if (array_key_exists($a->groupID(), $grouped) && in_array($a, $grouped[$a->groupID()])) {
                continue;
            }

            if (!array_key_exists($a->groupID(), $grouped)) {
                $grouped[$a->groupID()] = [$a];
            }

            foreach ($groupIdentifiables as $b) {
                if ($callback($a, $b)) {
                    $grouped[$a->groupID()][] = $b;
                }
            }
        }

        return $grouped;
    }
}
