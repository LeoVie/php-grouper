<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\Tests\Functional;

use LeoVie\PhpGrouper\PhpGrouperBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestingKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new PhpGrouperBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}