<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Shared\Domain\DataStructure\Enum;

/**
 * @method static ENABLED()
 * @method static DISABLED()
 * @method static WAIT_MODERATION()
 * @method static REJECTED()
 */
final class Status extends Enum
{
    private const ENABLED = 'enabled';
    private const DISABLED = 'disabled';
    private const WAIT_MODERATION = 'wait_moderation';
    private const REJECTED = 'rejected';
}
