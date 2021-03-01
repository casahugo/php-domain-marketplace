<?php

namespace App\Catalog\Infrastructure\DataFixtures;

use App\Catalog\Application\Brand\Create\CreateBrandCommand;
use App\Catalog\Application\Category\Create\CreateCategoryCommand;
use App\Catalog\Application\Company\Create\CreateCompanyCommand;
use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Catalog\Application\Shipping\Create\CreateShippingCommand;
use App\Catalog\Application\Tax\Create\CreateTaxCommand;
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

        $categoryCodes = $this->createCategory();
        $brandCodes = $this->createBrand();
        $taxCodes = $this->createTax();
        $shippingCodes = $this->createShipping();
        $companiesId = $this->createCompany();

        for ($i = 0; $i < 53; $i++) {
            $this->commandBus->dispatch(new CreateProductCommand(
                (string) $this->uuidGenerator->generate(),
                $faker->ean8,
                $faker->streetName,
                $faker->randomFloat(2, 10, 100),
                $faker->randomNumber(2),
                (string) $faker->randomElement($brandCodes),
                (string) $faker->randomElement($categoryCodes),
                (string) $faker->randomElement($companiesId),
                [
                    (string) $faker->randomElement($taxCodes),
                ],
                (string) $faker->randomElement($shippingCodes),
                null,
                null,
                null,
                [
                    __DIR__.'/picture/Aorus-geforce-gtx-1660-super-6G.jpg',
                ]
            ));
        }
    }

    private function createCategory(): array
    {
        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode1 = 'COMPUT', 'Computer'));
        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode2 = 'KEYBRD', 'Keyboard'));
        $this->commandBus->dispatch(new CreateCategoryCommand($categoryCode3 = 'SCRN', 'Screen'));

        return ['COMPUT', 'KEYBRD', 'SCRN'];
    }

    private function createBrand(): array
    {
        $this->commandBus->dispatch(new CreateBrandCommand($brandCode1 = 'TSB', 'Toshiba'));
        $this->commandBus->dispatch(new CreateBrandCommand($brandCode2 = 'SMSG', 'Samsung'));
        $this->commandBus->dispatch(new CreateBrandCommand($brandCode3 = 'HUA', 'Huawei'));

        return ['TSB', 'SMSG', 'HUA'];
    }

    private function createCompany(): array
    {
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

        return $companiesId;
    }

    private function createTax(): array
    {
        $this->commandBus->dispatch(new CreateTaxCommand('TVA_20', 'TVA 20%', .2));
        $this->commandBus->dispatch(new CreateTaxCommand('TVA_10', 'TVA 10%', .1));
        $this->commandBus->dispatch(new CreateTaxCommand('TVA_5', 'TVA 5.5%', .055));

        return ['TVA_20', 'TVA_10', 'TVA_5'];
    }

    private function createShipping(): array
    {
        $this->commandBus->dispatch(new CreateShippingCommand('UPS', 'UPS', 10));
        $this->commandBus->dispatch(new CreateShippingCommand('COL', 'Collissimo', 15));

        return ['UPS', 'COL'];
    }
}
