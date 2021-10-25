<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Service;

use LeoVie\PhpGrouper\Model\GroupIdentifiable;
use LeoVie\PhpGrouper\Sort\GroupIdentifiableSorter;

class Grouper
{
    public function __construct(private GroupIdentifiableSorter $groupIdentifiableSorter)
    {}

    /**
     * @param GroupIdentifiable[] $groupIdentifiables
     *
     * @return array<GroupIdentifiable[]>
     */
    public function group(array $groupIdentifiables): array
    {
        if (empty($groupIdentifiables)) {
            return [];
        }

        $result = [];
        $last = null;

        $sorted = $this->groupIdentifiableSorter->sort($groupIdentifiables);

        $group = $this->newGroup();

        foreach ($sorted as $i => $identity) {
            $s = $identity->groupID();

            $isNotFirst = $i > 0;
            $notSameIdentityAsLast = $s !== $last;

            if ($isNotFirst && $notSameIdentityAsLast) {
                $result = $this->addGroupToResult($group, $result);
                $group = $this->newGroup();
            }

            $group[] = $identity;

            $last = $s;
        }

        return $this->addGroupToResult($group, $result);
    }

    /**
     * @param GroupIdentifiable[] $group
     * @param array<GroupIdentifiable[]> $result
     *
     * @return array<GroupIdentifiable[]>
     */
    private function addGroupToResult(array $group, array $result): array
    {
        $result[] = $group;

        return $result;
    }

    /**
     * @return GroupIdentifiable[]
     */
    private function newGroup(): array
    {
        return [];
    }
}