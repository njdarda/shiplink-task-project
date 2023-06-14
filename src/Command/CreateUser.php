<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUser extends Command
{
    protected static $defaultName = 'app:create-user';

    private $entityManager;
    private $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Use this command to create a user.')
            ->addArgument('username', InputArgument::REQUIRED, 'Username (must be unique)')
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                "User password"
            )
            ->addArgument('roles', InputArgument::IS_ARRAY, 'User roles, e.g. ROLE_ADMIN ROLE_USER')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $roles = $input->getArgument('roles');

        $style = new SymfonyStyle($input, $output);

        $output->writeln([
            '<info>User Creator',
            '============',
            '',
            'Creating user:',
            "    username:    <comment>{$username}</comment>",
            "    password: <comment>{$password}</comment>",
            '    roles:    <comment>' . implode(', ', $roles) . '</comment>',
            '</info>',
        ]);

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setRoles($roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $style->success('');

        return Command::SUCCESS;
    }
}
