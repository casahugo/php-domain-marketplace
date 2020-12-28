<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

abstract class Enum
{
    private string|int|float $value;
    private static array $cache = [];
    private static array $instances = [];

    public static function of(string|int|float $value): static
    {
        $key = static::search($value);

        return static::byKey($key);
    }

    public static function isValid(string|int|float $value): bool
    {
        try {
            static::search($value);
        } catch (\InvalidArgumentException) {
            return false;
        }

        return true;
    }

    /**
     * @return array<string, static>
     */
    public static function values(): array
    {
        $array = [];
        foreach (static::toArray() as $constant) {
            $enum = static::of($constant);
            $array[$enum->getKey()] = $enum;
        }

        return $array;
    }

    private static function byKey(string $key): static
    {
        if (isset(self::$instances[static::class][$key])) {
            return self::$instances[static::class][$key];
        }

        $constants = static::toArray();
        if (!isset($constants[$key])) {
            $const = static::class."::$key";
            throw new \InvalidArgumentException("{$const} not defined");
        }

        self::$instances[static::class][$key] = new static($constants[$key]);

        return self::$instances[static::class][$key];
    }

    private static function search(string|int|float $value): string
    {
        $key = array_search($value, static::toArray(), true);
        if (false === $key || false === is_string($key)) {
            $class = static::class;
            throw new \InvalidArgumentException("Unknow value '$value' for enum '$class'");
        }

        return $key;
    }

    private static function toArray(): array
    {
        $class = static::class;
        if (!isset(self::$cache[$class])) {
            $reflection = new \ReflectionClass(static::class);
            self::$cache[$class] = $reflection->getConstants();
        }

        return self::$cache[$class];
    }

    final private function __construct(string|int|float $value)
    {
        $this->value = $value;
    }

    public function getKey(): string
    {
        return static::search($this->value);
    }

    public function getValue(): string|int|float
    {
        return $this->value;
    }

    public function equals(self $enum): bool
    {
        return $this === $enum;
    }

    final public static function __callStatic(string $key, array $arguments): static
    {
        return static::byKey($key);
    }

    final public function __clone(): void
    {
        throw new \LogicException('Enums are not cloneable');
    }

    final public function __sleep(): array
    {
        throw new \LogicException('Enums are not serializable');
    }

    final public function __wakeup(): void
    {
        throw new \LogicException('Enums are not serializable');
    }
}
