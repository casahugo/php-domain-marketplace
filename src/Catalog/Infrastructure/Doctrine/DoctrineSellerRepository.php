<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Seller\Id;
use App\Catalog\Domain\Seller\Seller;
use App\Catalog\Domain\Seller\SellerRepository;
use App\Shared\Domain\Email;

final class DoctrineSellerRepository implements SellerRepository
{
    public function get(Id $id): Seller
    {
        return new Seller($id, new Email('foo.bar@gmail.com'), 'Inc Corporation');
    }
}
