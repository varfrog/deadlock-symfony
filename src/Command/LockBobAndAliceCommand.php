<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\OutputHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LockBobAndAliceCommand extends Command
{
    protected static $defaultName = 'app:lock-bob-and-alice';

    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private OutputHelper $outputHelper;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        OutputHelper $outputHelper
    ) {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->outputHelper = $outputHelper;
    }

    protected function configure()
    {
        $this->setDescription('Locks users in the following order: Bob, Alice.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->transactional(function () use ($output) {
            $this->log($output, 'Locking Bob.');
            $this->userRepository->findOneByUsername('bob');
            $this->log($output, 'Locking Alice.');
            $this->userRepository->findOneByUsername('alice');
        });

        return Command::SUCCESS;
    }


    private function log(OutputInterface $output, string $message)
    {
        $output->writeln($this->outputHelper->getLogString(self::$defaultName, $message));
    }
}
