<?php

namespace App\Shared\Infrastructure\DataFixtures;

use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(
        private CommandBus $commandBus,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function load(ObjectManager $manager)
    {
        //$start = microtime(true);
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $this->commandBus->dispatch(new CreateProductCommand(
                $reference = $this->uuidGenerator->generate(),
                $faker->ean8,
                $faker->title,
                $faker->randomFloat(2, 10, 100),
                $faker->randomNumber(2),
                2,
                5,
                1,
                2,
            ));
        }

        //dump("end:", (float) microtime(true) - (float) $start);
    }
}
