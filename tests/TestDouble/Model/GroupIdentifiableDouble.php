<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Tests\TestDouble\Model;

use LeoVie\PhpGrouper\Model\GroupIdentifiable;

class GroupIdentifiableDouble implements GroupIdentifiable
{
    public function __construct(private string $groupID)
    {
    }

    public function groupID(): string
    {
        return $this->groupID;
    }
}