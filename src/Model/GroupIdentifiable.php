<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Model;

interface GroupIdentifiable
{
    public function groupID(): string;
}