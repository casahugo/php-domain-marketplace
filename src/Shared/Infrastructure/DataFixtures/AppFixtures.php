<?php

namespace App\Shared\Infrastructure\DataFixtures;

use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private CommandBus $commandBus,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $this->commandBus->dispatch(new CreateProductCommand(
            $reference = $this->uuidGenerator->generate(),
            'CODE01',
            'Laptop',
            12.23,
            100,
            2,
            5,
            1,
            2,
            'Best product'
        ));

        $this->commandBus->dispatch(new CreateProductCommand(
            $reference = $this->uuidGenerator->generate(),
            'CODE02',
            'Mouse',
            5.23,
            100,
            3,
            6,
            1,
            2,
            'Best mouse'
        ));

        $this->commandBus->dispatch(new CreateProductCommand(
            $reference = $this->uuidGenerator->generate(),
            'CODE03',
            'Keyboard',
            12.98,
            100,
            3,
            6,
            1,
            2,
            'Best keyboard'
        ));
    }
}
