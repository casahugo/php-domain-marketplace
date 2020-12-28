<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\PHPStan;

use App\Shared\Domain\DataStructure\Enum;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

final class EnumMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if ($classReflection->isSubclassOf(Enum::class)) {
            return $classReflection->getNativeReflection()->hasConstant($methodName);
        }

        return false;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): EnumMethodReflection
    {
        return new EnumMethodReflection($classReflection, $methodName);
    }
}
