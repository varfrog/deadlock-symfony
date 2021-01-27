<?php

declare(strict_types=1);

namespace App\Command;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use App\Service\OutputHelper;
use Doctrine\DBAL\Exception\DeadlockException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LockAliceAndBobCommand extends Command
{
    protected static $defaultName = 'app:lock-alice-and-bob';

    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private OutputHelper $outputHelper;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        OutputHelper $outputHelper,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->outputHelper = $outputHelper;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setDescription('Locks users in the following order: Alice, Bob.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager->transactional(function () use ($output) {
            $this->log($output, 'Locking Alice and sleeping.');
            $this->userRepository->findOneByUsername(AppFixtures::USERNAME_ALICE);
            sleep(5);
            $this->log($output, 'Locking Bob.');
            try {
                $this->userRepository->findOneByUsername(AppFixtures::USERNAME_BOB);
            } catch (DeadlockException $exception) {
                $this->logger->error('Got a deadlock in ' . self::$defaultName, ['exception' => $exception]);
                $this->log($output, 'Got a deadlock.');
            }
        });

        return Command::SUCCESS;
    }

    private function log(OutputInterface $output, string $message)
    {
        $output->writeln($this->outputHelper->getLogString(self::$defaultName, $message));
    }
}
