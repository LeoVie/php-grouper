<?php

namespace LeoVie\PhpGrouper\Strategy;

use LeoVie\PhpGrouper\Model\GroupIdentifiable;

interface GroupStrategy
{
    public function groupCriterion(GroupIdentifiable $a, GroupIdentifiable $b): bool;
}