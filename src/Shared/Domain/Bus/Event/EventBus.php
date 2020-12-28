<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

interface EventBus
{
    public function dispatch(DomainEvent ...$events): void;
}
