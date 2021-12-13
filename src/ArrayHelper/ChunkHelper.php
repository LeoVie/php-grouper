<?php

namespace LeoVie\PhpGrouper\ArrayHelper;

class ChunkHelper
{
    public function extractChunk(array $array, int $chunkCount, int $index): array
    {
        $lengthOfEachChunk = (int)ceil(count($array) / $chunkCount);
        $chunks = array_chunk($array, $lengthOfEachChunk);

        return $chunks[$index] ?? [];
    }
}