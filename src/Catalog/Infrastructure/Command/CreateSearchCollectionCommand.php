<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Command;

use App\Catalog\Domain\Product\ProductProjector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateSearchCollectionCommand extends Command
{
    protected static $defaultName = 'app:create-search';

    public function __construct(private ProductProjector $projector)
    {
        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create search collection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->projector->reset();
        $this->projector->configure();

        return Command::SUCCESS;
    }
}
