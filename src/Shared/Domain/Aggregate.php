<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

abstract class Aggregate
{
    private array $domainEvents = [];

    final public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final public function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
