<?php

namespace App\Infrastructure\Product\Command;

use App\Entity\Product;
use App\Infrastructure\Product\Repository\ProductRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-example-data',
    description: 'Add a short description for your command',
)]
class LoadExampleDataCommand extends Command
{
    public function __construct(
        private readonly ProductRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln('Adding 10 example products...');

        for ($i = 1; $i <= 10; $i++) {
            $product = new Product(
                name: 'Product ' . $i,
                price: rand(100, 1000) / 10,
                stock: rand(0, 50),
            );
            $product->setIsActive((bool) rand(0, 1));

            $this->repository->save($product);

            $output->writeln('Product ' . $i . ' added to database.');
        }

        $io->success('Products added!');

        return Command::SUCCESS;
    }
}
