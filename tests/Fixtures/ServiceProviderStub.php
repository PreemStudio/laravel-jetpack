<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use PreemStudio\Jetpack\Package\AbstractServiceProvider;

final class ServiceProviderStub extends AbstractServiceProvider
{
    protected $defer = false;

    public function packageRegistered(): void
    {
        $this->app->singleton('testbench.foostub', function ($app): FooStub {
            return new FooStub('baz');
        });

        $this->app->alias('testbench.foostub', FooStub::class);
    }

    public function provides(): array
    {
        return [
            'testbench.foostub',
        ];
    }
}
