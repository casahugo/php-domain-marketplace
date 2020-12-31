<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain\DataStructure;

use App\Shared\Domain\DataStructure\Enum;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

final class EnumTest extends TestCase
{
    public function testNamedConstructorOf(): void
    {
        $foo = PrivateEnum::of('foo_value');
        self::assertInstanceOf(PrivateEnum::class, $foo);
        self::assertSame(PrivateEnum::FOO(), $foo);

        $first = IntegerEnum::of(1);
        self::assertInstanceOf(IntegerEnum::class, $first);
        self::assertSame(IntegerEnum::FIRST(), $first);
    }

    public function testAnExceptionThrowsOnUnknownValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        PrivateEnum::of('unknown');
    }

    public function testEnumAccessWithPublicValues(): void
    {
        $foo = PublicEnum::FOO();
        $bar = PublicEnum::BAR();

        self::assertInstanceOf(Enum::class, $foo);
        self::assertInstanceOf(Enum::class, $bar);

        self::assertInstanceOf(PublicEnum::class, $foo);
        self::assertInstanceOf(PublicEnum::class, $bar);

        self::assertNotEquals($foo, $bar);
    }

    public function testEnumAccessWithProtectedValues(): void
    {
        $foo = ProtectedEnum::FOO();
        $bar = ProtectedEnum::BAR();

        self::assertInstanceOf(Enum::class, $foo);
        self::assertInstanceOf(Enum::class, $bar);

        self::assertInstanceOf(ProtectedEnum::class, $foo);
        self::assertInstanceOf(ProtectedEnum::class, $bar);

        self::assertNotEquals($foo, $bar);
    }

    public function testEnumAccessWithPrivateValues(): void
    {
        $foo = PrivateEnum::FOO();
        $bar = PrivateEnum::BAR();

        self::assertInstanceOf(Enum::class, $foo);
        self::assertInstanceOf(Enum::class, $bar);

        self::assertInstanceOf(PrivateEnum::class, $foo);
        self::assertInstanceOf(PrivateEnum::class, $bar);

        self::assertNotEquals($foo, $bar);
    }

    public function testAnExceptionThrowsOnUsingUnknownValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        PrivateEnum::UNKNOWN();
    }

    public function testGetKey(): void
    {
        self::assertSame('FOO', PublicEnum::FOO()->getKey());
        self::assertSame('BAR', ProtectedEnum::BAR()->getKey());
        self::assertSame('BAR', PrivateEnum::BAR()->getKey());
        self::assertSame('FIRST', IntegerEnum::FIRST()->getKey());
    }

    public function testGetValue(): void
    {
        self::assertSame('foo_value', PublicEnum::FOO()->getValue());
        self::assertSame('bar_value', ProtectedEnum::BAR()->getValue());
        self::assertSame('bar_value', PrivateEnum::BAR()->getValue());
        self::assertSame(1, IntegerEnum::FIRST()->getValue());
    }

    public function testSameEnumValueOnDifferentClassNotConflict(): void
    {
        self::assertSame(PublicEnum::FOO(), PublicEnum::FOO());
        self::assertNotSame(PublicEnum::FOO(), PrivateEnum::FOO());
    }

    public function testEqualsMethod(): void
    {
        self::assertTrue(PublicEnum::FOO()->equals(PublicEnum::FOO()));
        self::assertFalse(PublicEnum::FOO()->equals(PrivateEnum::FOO()));
    }

    public function testAnExceptionThrowOnEnumCloning(): void
    {
        $foo = PublicEnum::FOO();

        $this->expectException(LogicException::class);

        clone $foo;
    }

    public function testAnExceptionThrowOnEnumSerialization(): void
    {
        $foo = PublicEnum::FOO();

        $this->expectException(LogicException::class);

        serialize($foo);
    }

    public function testIsValid(): void
    {
        self::assertTrue(PublicEnum::isValid('foo_value'));
    }

    public function testIsValidOnUnknownValue(): void
    {
        self::assertFalse(PublicEnum::isValid('unknown'));
    }

    public function testArrayTransformation(): void
    {
        $values = PublicEnum::values();
        self::assertIsArray($values);
        self::assertSame($values['FOO'], PublicEnum::FOO());
        self::assertSame($values['BAR'], PublicEnum::BAR());

        $values = PrivateEnum::values();
        self::assertIsArray($values);
        self::assertSame($values['FOO'], PrivateEnum::FOO());
        self::assertSame($values['BAR'], PrivateEnum::BAR());
    }

    public function testMatchEvaluation(): void
    {
        $value = ProtectedEnum::FOO();
        $result = match ($value) {
            ProtectedEnum::FOO() => true,
            default => false,
        };

        self::assertTrue($result);
    }
}

/**
 * @method static static FOO()
 * @method static static BAR()
 */
class PrivateEnum extends Enum
{
    private const FOO = 'foo_value';
    private const BAR = 'bar_value';
}

/**
 * @method static static FIRST()
 * @method static static SECOND()
 */
class IntegerEnum extends Enum
{
    private const FIRST = 1;
    private const SECOND = 2;
}

/**
 * @method static static FOO()
 * @method static static BAR()
 */
class ProtectedEnum extends Enum
{
    protected const FOO = 'foo_value';
    protected const BAR = 'bar_value';
}

/**
 * @method static static FOO()
 * @method static static BAR()
 */
class PublicEnum extends Enum
{
    public const FOO = 'foo_value';
    public const BAR = 'bar_value';
}
