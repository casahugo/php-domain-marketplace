<?php

namespace App\Catalog\Infrastructure\DataFixtures;

use App\Catalog\Application\Brand\Create\CreateBrandCommand;
use App\Catalog\Application\Category\Create\CreateCategoryCommand;
use App\Catalog\Application\Company\Create\CreateCompanyCommand;
use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Uuid\UuidGenerator;
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

        $companiesId = [
            (string) $this->uuidGenerator->generate(),
            (string) $this->uuidGenerator->generate(),
        ];

        $this->commandBus->dispatch(new CreateCompanyCommand(
            $companiesId[0],
            'contact@hoboken.io',
            'Hokoken'
        ));

        $this->commandBus->dispatch(new CreateCompanyCommand(
            $companiesId[1],
            'contact@inc.corp',
            'Inc Corporation'
        ));

        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode1 = 'COMPUT', 'Computer'));
        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode2 = 'KEYBRD', 'Keyboard'));
        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode3 = 'SCRN', 'Screen'));

        $this->commandBus->dispatch(new CreateBrandCommand($brandCode1 = 'TSB', 'Toshiba'));
        $this->commandBus->dispatch(new CreateBrandCommand($brandCode2 = 'SMSG', 'Samsung'));
        $this->commandBus->dispatch(new CreateBrandCommand($brandCode3 = 'HUA', 'Huawei'));

        for ($i = 0; $i < 100; $i++) {
            $this->commandBus->dispatch(new CreateProductCommand(
                (string) $this->uuidGenerator->generate(),
                $faker->ean8,
                $faker->streetName,
                $faker->randomFloat(2, 10, 100),
                $faker->randomNumber(2),
                (string) $faker->randomElement([$brandCode1, $brandCode2, $brandCode3]),
                (string) $faker->randomElement([$categoryCode1, $categoryCode2, $categoryCode3]),
                (string) $faker->randomElement($companiesId),
                ['TVA_20'],
            ));
        }
    }
}
