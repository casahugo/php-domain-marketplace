<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

class Collection implements \Iterator
{
    private string $type;
    private array $data;

    public function __construct(string $type)
    {
        $this->type = $type;
        $this->data = [];
    }

    public function add(mixed ...$values): static
    {
        $set = $this;

        foreach ($values as $value) {
            if (!$value instanceof $this->type) {
                throw new \InvalidArgumentException();
            }

            if ($this->contains($value)) {
                continue;
            }

            $set = clone $set;
            $set->data[] = $value;
        }

        return $set;
    }

    public function remove(mixed ...$values): static
    {
        $set = $this;

        foreach ($values as $value) {
            if (!$value instanceof $this->type) {
                throw new \InvalidArgumentException();
            }

            if (!$this->contains($value)) {
                continue;
            }

            $set = clone $set;
            unset($set->data[array_search($value, $this->data)]);
        }

        return $set;
    }

    public function count(): int
    {
        return \count($this->data);
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function contains(mixed $value): bool
    {
        if (!$value instanceof $this->type) {
            throw new \InvalidArgumentException();
        }

        return \in_array($value, $this->data, true);
    }

    public function findFirst(callable $f): mixed
    {
        foreach ($this->data as $value) {
            if ($f($value)) {
                return $value;
            }
        }

        return null;
    }

    public function hasOne(callable $f): bool
    {
        foreach ($this->data as $value) {
            if ($f($value)) {
                return true;
            }
        }

        return false;
    }

    public function filter(callable $f): static
    {
        $data = array_filter($this->data, $f);

        $set = clone $this;
        $set->data = $data;

        return $set;
    }

    public function reduce(callable $f, mixed $initial): mixed
    {
        return array_reduce($this->data, $f, $initial);
    }

    public function map(callable $f): static
    {
        $data = array_map($f, $this->data);

        $set = clone $this;
        $set->data = $data;

        return $set;
    }

    public function toArray(callable $fn): array
    {
        return array_map($fn, $this->data);
    }

    public function sort(callable $f): static
    {
        $data = $this->data;

        usort($data, $f);

        $set = clone $this;
        $set->data = $data;

        return $set;
    }

    /** @return mixed */
    public function current(): mixed
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): int|string|null
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return null !== $this->key();
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    public function first(): mixed
    {
        return reset($this->data);
    }

    public function merge(self $collection): static
    {
        $self = clone $this;

        foreach ($collection as $item) {
            $self = $self->add($item);
        }

        return $self;
    }

    public function diff(Collection $set): static
    {
        $self = clone $this;
        $self->data = array_diff($this->data, $set->data);

        return $self;
    }
}
