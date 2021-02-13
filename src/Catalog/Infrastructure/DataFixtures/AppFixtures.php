<?php

namespace App\Catalog\Infrastructure\DataFixtures;

use App\Catalog\Application\Brand\Create\CreateBrandCommand;
use App\Catalog\Application\Category\Create\CreateCategoryCommand;
use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Uuid\UuidGenerator;
use App\Shared\Infrastructure\Uuid\Uuid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(
        private CommandBus $commandBus,
        private UuidGenerator $uuidGenerator
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode1 = 'COMPUT', 'Computer'));
        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode2 = 'KEYBRD', 'Keyboard'));
        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode3 = 'SCRN', 'Screen'));

        $this->commandBus->dispatch(new CreateBrandCommand($brandCode1 = 'TSB', 'Toshiba'));
        $this->commandBus->dispatch(new CreateBrandCommand($brandCode2 = 'SMSG', 'Samsung'));
        $this->commandBus->dispatch(new CreateBrandCommand($brandCode3 = 'HUA', 'Huawei'));

        for ($i = 0; $i < 100; $i++) {
            $this->commandBus->dispatch(new CreateProductCommand(
                $reference = $this->uuidGenerator->generate(),
                $faker->ean8,
                $faker->streetName,
                $faker->randomFloat(2, 10, 100),
                $faker->randomNumber(2),
                (string) $faker->randomElement([$brandCode1, $brandCode2, $brandCode3]),
                (string) $faker->randomElement([$categoryCode1, $categoryCode2, $categoryCode3]),
                new Uuid('01E439TP9XJZ9RPFH3T1PYBCR8'),
                ['TVA_20'],
            ));
        }
    }
}
