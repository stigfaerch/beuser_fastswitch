<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerConfigurator $container, ContainerBuilder $containerBuilder): void {
    $services = $container->services();
    $services->defaults()->private()->autowire()->autoconfigure();

    $services->load('JosefGlatz\\BeuserFastswitch\\', __DIR__ . '/../Classes/*');
};
