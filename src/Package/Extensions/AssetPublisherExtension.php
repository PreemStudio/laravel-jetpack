<?php

declare(strict_types=1);

namespace PreemStudio\Jetpack\Package\Extensions;

use PreemStudio\Jetpack\Package\AbstractServiceProvider;
use PreemStudio\Jetpack\Package\Contracts\Extension;
use PreemStudio\Jetpack\Package\Package;

final class AssetPublisherExtension implements Extension
{
    public function execute(AbstractServiceProvider $serviceProvider, Package $package): void
    {
        if ($package->hasAssets) {
            $serviceProvider->forwardPublishes([
                $package->rootPath('resources/dist') => public_path("vendor/{$package->shortName()}"),
            ], "{$package->shortName()}-assets");
        }
    }
}
