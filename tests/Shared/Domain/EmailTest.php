<?php

declare(strict_types=1);

namespace App\Tests\Shared\Domain;

use App\Shared\Domain\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    /** @dataProvider emailValid */
    public function testValidEmail(string $email): void
    {
        self::assertSame($email, (string) (new Email($email)));
    }

    /** @dataProvider emailInvalid */
    public function testInvalidEmail(string $email): void
    {
        $this->expectException(\UnexpectedValueException::class);

        new Email($email);
    }

    public function emailValid(): array
    {
        return [
            ['email@tld.fr'],
            ['email+login@gmail.com'],
            ['email.0001@foo.bar'],
            ['email.0001+bar@foo.baz'],
        ];
    }

    public function emailInvalid(): array
    {
        return [
            ['email@tld.'],
            ['email+logingmail.com'],
            ['@foo.bar'],
            ['123'],
        ];
    }
}
