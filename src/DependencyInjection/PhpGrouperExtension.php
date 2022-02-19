<?php

declare(strict_types=1);

namespace LeoVie\PhpGrouper\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PhpGrouperExtension extends Extension
{
    const CONFIG_DIR = __DIR__ . '/../../config/';

    /**
     * @param mixed[] $configs
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configDir = new FileLocator(self::CONFIG_DIR);

        $loader = new YamlFileLoader($container, $configDir);
        $loader->load('services.yaml');
    }
}