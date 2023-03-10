<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

// Generic
expect()->extend('toBeSubclassOf', function (string $class) {
    $reflection = new ReflectionClass($this->value);
    $provider   = new ReflectionClass($class);

    expect($reflection->isSubclassOf($provider))->toBeTrue();
});

// Service Provider
expect()->extend('toBeServiceProvider', function () {
    expect($this->value)->toBeSubclassOf(ServiceProvider::class);
});

expect()->extend('toBeServiceProviderWithServices', function () {
    $class      = $this->value;
    $reflection = new ReflectionClass($class);

    $method = $reflection->getMethod('provides');
    $method->setAccessible(true);

    expect($method->invoke(new $class($this->app)))->toBeArray();
});

// Facade
expect()->extend('toBeFacade', function (string $accessor, string $root) {
    expect($this->value)->toMatchFacadeSubclass();
    expect($this->value)->toMatchFacadeAccessor($accessor);
    expect($this->value)->toMatchFacadeRoot($root);
});

expect()->extend('toMatchFacadeSubclass', function () {
    expect($this->value)->toBeSubclassOf(Facade::class);
});

expect()->extend('toMatchFacadeAccessor', function (string $accessor) {
    $reflection = new ReflectionClass($this->value);
    $method     = $reflection->getMethod('getFacadeAccessor');
    $method->setAccessible(true);

    expect($method->invoke(null))->toEqual($accessor);
});

expect()->extend('toMatchFacadeRoot', function (string $root) {
    $reflection = new ReflectionClass($this->value);
    $method     = $reflection->getMethod('getFacadeRoot');
    $method->setAccessible(true);

    expect($method->invoke(null))->toBeInstanceOf($root);
});

expect()->extend('toBeFacadeProvider', function (string $accessor) {
    $provider   = $this->value;
    $reflection = new ReflectionClass($provider);
    $method     = $reflection->getMethod('provides');
    $method->setAccessible(true);

    expect($method->invoke(new $provider($this->app)))->toContain($accessor);
});

// Container
expect()->extend('toBeInjectable', function () {
    $name       = $this->value;
    $injectable = true;

    try {
        do {
            $class = 'testBenchStub'.Str::random();
        } while (class_exists($class));

        eval("
            class $class
            {
                protected readonly mixed \$object;

                public function __construct(\\$name \$object)
                {
                    \$this->object = \$object;
                }

                public function getInjectedObject(): mixed
                {
                    return \$this->object;
                }
            }
        ");

        expect(app()->make($class)->getInjectedObject())->toBeInstanceOf($name);
    } catch (Exception) {
        $injectable = false;
    }

    expect($injectable)->toBeTrue();
});
