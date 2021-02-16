<?php
/**
 * Created by PhpStorm.
 * User: tomashladky
 * Date: 17/07/2019
 * Time: 08:54
 */

namespace App\Commands;


use App\Model\User\UserData;
use App\Model\User\UserFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'create-user';

    /** @var UserFacade */
    protected $userFacade;

    /**
     * CreateUserCommand constructor.
     * @param UserFacade $userFacade
     */
    public function __construct(UserFacade $userFacade)
    {
        $this->userFacade = $userFacade;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...')

        //    ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
        //    ->addArgument('email', InputArgument::REQUIRED, 'User email')
        //    ->addArgument('password', $this->requirePassword ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'User password')
        ;


    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = null;
        $plainPassword = null;
        $email = null;

        $outputStyle = new OutputFormatterStyle('cyan', 'default', ['bold']);
        $output->getFormatter()->setStyle('init', $outputStyle);

        $outputStyle = new OutputFormatterStyle('green', 'default', ['bold']);
        $output->getFormatter()->setStyle('success', $outputStyle);

        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '',
            '<init>User Creator</init>',
            '',
        ]);

        $helper = $this->getHelper('question');

        $question0 = new Question('Enter username: ', false);
        $username = $helper->ask($input, $output, $question0);

        if(strlen($username) == 0){
            return $this->commandError($output, "Field 'username' cannot be empty");
        }else if(strlen($username) < 3){
            return $this->commandError($output, "Field 'username' should have at least 3 characters");
        }

        $question1= new Question('Enter password: ', false);
        $plainPassword = $helper->ask($input, $output, $question1);

        if(strlen($plainPassword) == 0){
            return $this->commandError($output, "Field 'password' cannot be empty");
        }else if(strlen($plainPassword) < 5){
            return $this->commandError($output, "Field 'password' should have at least 5 characters");
        }
        $question2 = new Question('Enter firstname: ', false);
        $firstname = $helper->ask($input, $output, $question2);

        if(strlen($firstname) == 0){
            return $this->commandError($output, "Field 'firstname' cannot be empty");
        }else if(strlen($firstname) < 3){
            return $this->commandError($output, "Field 'firstname' should have at least 3 characters");
        }
        $question3 = new Question('Enter lastname: ', false);
        $lastname = $helper->ask($input, $output, $question3);

        if(strlen($lastname) == 0){
            return $this->commandError($output, "Field 'lastname' cannot be empty");
        }else if(strlen($lastname) < 3){
            return $this->commandError($output, "Field 'lastname' should have at least 3 characters");
        }
        $output->writeln("<init>Creating user...</init>");

        $userData = new UserData();
        $userData->username = $username;
        $userData->password = $plainPassword;
        $userData->firstname = $firstname;
        $userData->lastname = $lastname;

        $this->userFacade->create($userData);
        // outputs a message followed by a "\n"
        $output->writeln('<success>User created successfully</success>');
        return 0;
    }

    public function encodePassword($user, $plainPassword, UserPasswordEncoderInterface $encoder){
        return $encoder->encodePassword($user, $plainPassword);
    }

    public function commandError(OutputInterface $output, $error){
        $outputStyle = new OutputFormatterStyle('red', 'default', ['bold']);
        $output->getFormatter()->setStyle('error', $outputStyle);

        $output->writeln("<error>".$error."</error>");
    }
}