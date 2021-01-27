<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LockAliceAndBobCommand extends Command
{
    protected static $defaultName = 'app:lock-alice-and-bob';

    protected function configure()
    {
        $this->setDescription('Locks users in the following order: Alice, Bob.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        return Command::SUCCESS;
    }
}
