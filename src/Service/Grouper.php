<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Service;

use Amp\Parallel\Worker;
use Amp\Promise;
use LeoVie\PhpGrouper\ArrayHelper\ChunkHelper;
use LeoVie\PhpGrouper\Model\GroupIdentifiable;
use LeoVie\PhpGrouper\Strategy\GroupStrategy;
use Opis\Closure\SerializableClosure;

class Grouper
{
    public function __construct(private ChunkHelper $chunkHelper)
    {
    }

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
     * @param GroupIdentifiable[] $groupIdentifiables
     *
     * @return array<GroupIdentifiable[]>
     */
    public function groupByCallback(array $groupIdentifiables, callable $callback): array
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


    /**
     * @param GroupIdentifiable[] $groupIdentifiables
     *
     * @return array<GroupIdentifiable[]>
     */
    public function groupByGroupStrategyParallel(array $groupIdentifiables, GroupStrategy $groupStrategy): array
    {
        if (empty($groupIdentifiables)) {
            return [];
        }

        $countOfThreads = 4;

        $promises = [];
        for ($i = 0; $i < $countOfThreads; $i++) {
            $chunk = $this->chunkHelper->extractChunk($groupIdentifiables, $countOfThreads, $i);
            $promises[] = Worker\enqueueCallable(
                new SerializableClosure(
                    fn(array $chunk, GroupStrategy $groupStrategy) => $this->groupByGroupStrategy($chunk, $groupStrategy)
                ),
                $chunk,
                $groupStrategy
            );
        }

        $groupedPerPromise = Promise\wait(Promise\all($promises));

        var_dump($groupedPerPromise);

        $groupedFlat = [];
        foreach ($groupedPerPromise as $grouped) {
            foreach ($grouped as $key => $value) {
                foreach ($value as $x) {
                    $groupedFlat[$key][] = $x;
                }
            }
        }

        return $groupedFlat;
    }

    private function groupByGroupStrategy(array $groupIdentifiables, GroupStrategy $groupStrategy): array
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
                if ($groupStrategy->groupCriterion($a, $b)) {
                    $grouped[$a->groupID()][] = $b;
                }
            }
        }

        return $grouped;
    }
}