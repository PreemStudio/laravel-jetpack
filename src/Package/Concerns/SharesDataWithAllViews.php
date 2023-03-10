<?php

declare(strict_types=1);

namespace PreemStudio\Jetpack\Package\Concerns;

trait SharesDataWithAllViews
{
    public array $sharedViewData = [];

    public function sharesDataWithAllViews(string $name, mixed $value): static
    {
        $this->sharedViewData[$name] = $value;

        return $this;
    }
}
