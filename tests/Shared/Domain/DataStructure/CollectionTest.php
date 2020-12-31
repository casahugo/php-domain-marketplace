<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain\DataStructure;

use App\Shared\Domain\DataStructure\Collection;
use PHPUnit\Framework\TestCase;

class Foo
{
    public int $id;

    public function __construct(int $id = 1)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}

class FooCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Foo::class);
    }
}

final class CollectionTest extends TestCase
{
    public function testAjoutDeDonnees(): void
    {
        $set1 = new Collection(Foo::class);
        $set2 = $set1->add(new Foo());

        self::assertNotSame($set1, $set2);
        self::assertInstanceOf(Collection::class, $set2);
    }

    public function testAjoutDePlusieursDonnees(): void
    {
        $set1 = new Collection(Foo::class);
        $set2 = $set1->add(new Foo(), new Foo());
        $set3 = $set2->add(new Foo());

        self::assertNotSame($set1, $set2);
        self::assertInstanceOf(Collection::class, $set2);
        self::assertCount(2, $set2);
        self::assertCount(3, $set3);
    }

    public function testRetraitDeDonnees(): void
    {
        $set1 = (new FooCollection())
            ->add($foo1 = new Foo(4))
            ->add($foo2 = new Foo(5));

        $set2 = $set1->remove($foo2);

        self::assertNotSame($set1, $set2);
        self::assertInstanceOf(Collection::class, $set2);
        self::assertInstanceOf(FooCollection::class, $set2);
        self::assertCount(2, $set1);
        self::assertCount(1, $set2);
        self::assertTrue($set2->contains($foo1));
    }

    public function testRetraitDePlusieursDonnees(): void
    {
        $set1 = (new FooCollection())
            ->add($foo1 = new Foo(4))
            ->add($foo2 = new Foo(5));

        $set2 = $set1->remove($foo1, $foo2);

        self::assertNotSame($set1, $set2);
        self::assertInstanceOf(Collection::class, $set2);
        self::assertInstanceOf(FooCollection::class, $set2);
        self::assertCount(2, $set1);
        self::assertCount(0, $set2);
    }

    public function testUneErreurEstLeveeLorsDeLAjoutDUneDonneeAvecUneMauvaiseValeur(): void
    {
        $set = new Collection(Foo::class);

        self::expectException(\InvalidArgumentException::class);

        $set->add(new \stdClass());
    }

    public function testCompterElementsDuSet(): void
    {
        $set = new Collection(Foo::class);
        self::assertSame(0, $set->count());

        $set = $set->add(new Foo());
        self::assertSame(1, $set->count());

        $set = $set->add(new Foo());
        self::assertSame(2, $set->count());

        $set = $set->add(new Foo());
        self::assertSame(3, $set->count());
    }

    public function testContientElement(): void
    {
        $value = new Foo();

        $set = new Collection(Foo::class);
        self::assertFalse($set->contains($value));

        $set = $set->add($value);
        self::assertTrue($set->contains($value));
    }

    public function testMapEstVide(): void
    {
        $set = new Collection(Foo::class);
        self::assertTrue($set->isEmpty());

        $set = $set->add(new Foo());
        self::assertFalse($set->isEmpty());
    }

    public function testIteration(): void
    {
        $set = new Collection(Foo::class);
        $set = $set->add($value1 = new Foo());
        $set = $set->add($value2 = new Foo());
        $set = $set->add($value3 = new Foo());

        $expected = [
            $value1,
            $value2,
            $value3,
        ];

        self::assertSame($expected, iterator_to_array($set));
    }

    public function testFiltreElement(): void
    {
        $set = new Collection(Foo::class);
        $set = $set->add(new Foo());
        $set = $set->add(new Foo());
        $set = $set->add($value = new Foo());

        $filteredCollection = $set->filter(
            static function(Foo $element) use ($value): bool {
                return $element === $value;
            }
        );

        self::assertInstanceOf(Collection::class, $filteredCollection);
        self::assertSame(1, $filteredCollection->count());
        self::assertTrue($filteredCollection->contains($value));
    }

    public function testFiltreCollection(): void
    {
        $set = new FooCollection();
        $set = $set->add(new Foo());
        $set = $set->add(new Foo());
        $set = $set->add($value = new Foo());

        $filteredCollection = $set->filter(
            static function(Foo $element) use ($value): bool {
                return $element === $value;
            }
        );

        self::assertInstanceOf(FooCollection::class, $filteredCollection);
        self::assertSame(1, $filteredCollection->count());
        self::assertTrue($filteredCollection->contains($value));
    }

    public function testTrie(): void
    {
        $set = new FooCollection();
        $set = $set->add($value1 = new Foo(4));
        $set = $set->add($value2 = new Foo(1));
        $set = $set->add($value3 = new Foo(2));

        $sortedCollection = $set->sort(
            static function(Foo $element1, Foo $element2): int {
                return $element1->id <=> $element2->id;
            }
        );

        self::assertInstanceOf(Collection::class, $sortedCollection);
        self::assertInstanceOf(FooCollection::class, $sortedCollection);

        $expected = [
            $value2,
            $value3,
            $value1,
        ];

        self::assertEquals($expected, iterator_to_array($sortedCollection));
    }

    public function testMethodeHasone(): void
    {
        $set = new Collection(Foo::class);
        $set = $set->add(new Foo(4));
        $set = $set->add(new Foo(1));
        $set = $set->add(new Foo(2));

        self::assertTrue($set->hasOne(static function(Foo $element): bool {
            return $element->id === 1;
        }));

        self::assertTrue($set->hasOne(static function(Foo $element): bool {
            return $element->id === 2;
        }));

        self::assertFalse($set->hasOne(static function(Foo $element): bool {
            return $element->id === 0;
        }));
    }

    public function testMethodeFindFirst(): void
    {
        $set = new Collection(Foo::class);
        $set = $set->add(new Foo(4));
        $set = $set->add($expected = new Foo(1));
        $set = $set->add(new Foo(2));
        $set = $set->add(new Foo(1));

        $actual = $set->findFirst(static function(Foo $element): bool {
            return $element->id === 1;
        });
        self::assertInstanceOf(Foo::class, $actual);
        self::assertSame($expected, $actual);

        $actual = $set->findFirst(static function(Foo $element): bool {
            return $element->id === 0;
        });
        self::assertNull($actual);
    }

    public function testMethodeFirst(): void
    {
        $set = new Collection(Foo::class);
        $set = $set->add($expected = new Foo(3));
        $set = $set->add(new Foo(2));
        $set = $set->add(new Foo(1));

        $actual = $set->first();
        self::assertInstanceOf(Foo::class, $actual);
        self::assertSame($expected, $actual);
    }

    public function testMethodeReduce(): void
    {
        $set = (new Collection(Foo::class))
            ->add(new Foo(1))
            ->add(new Foo(2))
            ->add(new Foo(3));

        self::assertSame(16, $set->reduce(function(int $carry, Foo $foo) {
            $carry += $foo->id;

            return $carry;
        }, 10));
    }

    public function testMethodeMap(): void
    {
        $set = (new FooCollection())
            ->add(new Foo(1))
            ->add(new Foo(2))
            ->add(new Foo(3));

        $expected = (new FooCollection())
            ->add(new Foo(2))
            ->add(new Foo(3))
            ->add(new Foo(4));

        self::assertEquals($expected, $set->map(function(Foo $foo) {
            $foo->id++;

            return $foo;
        }));

        self::assertInstanceOf(FooCollection::class, $expected);
    }

    public function testTransformationEnTableau(): void
    {
        $set = (new Collection(Foo::class))
            ->add(new Foo(1))
            ->add(new Foo(2))
            ->add(new Foo(3));

        $expected = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];

        $fn = fn(Foo $foo): array => ['id' => $foo->id];

        self::assertSame($expected, $set->toArray($fn));
    }

    public function testMerge(): void
    {
        $set = (new FooCollection())
            ->add(new Foo(1))
            ->add(new Foo(2));

        $set2 = (new Collection(Foo::class))->add($foo = new Foo(3));

        $result = $set->merge($set2);

        self::assertCount(3, $result);
        self::assertTrue($result->contains($foo));
        self::assertInstanceOf(FooCollection::class, $result);
    }

    public function testDiff(): void
    {
        $set = (new FooCollection())
            ->add($foo1 = new Foo(24))
            ->add($foo2 = new Foo(42));

        $set2 = (new FooCollection())
            ->add($foo2)
            ->add($foo3 = new Foo(64));

        $diff = $set->diff($set2);

        self::assertInstanceOf(FooCollection::class, $diff);
        self::assertCount(1, $diff);
        self::assertSame($foo1, $diff->first());
    }
}
