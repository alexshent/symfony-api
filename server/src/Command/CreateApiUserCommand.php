<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-api-user',
    description: 'Create user for processing API requests',
)]
class CreateApiUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        //        $this
        //            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        //        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //        $io = new SymfonyStyle($input, $output);
        //        $arg1 = $input->getArgument('arg1');
        //
        //        if ($arg1) {
        //            $io->note(sprintf('You passed an argument: %s', $arg1));
        //        }
        //
        //        if ($input->getOption('option1')) {
        //            // ...
        //        }
        //
        //        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        $user = $this->userRepository->findOneBy([
            'username' => 'apiuser',
        ]);

        if (null === $user) {
            $user = new User();
            $user->setUsername('apiuser');
            $user->setRoles(['ROLE_USER']);

            $plaintextPassword = '1';
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            $this->userRepository->save($user);
        }

        return Command::SUCCESS;
    }
}
