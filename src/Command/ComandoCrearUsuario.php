<?php

namespace App\Command;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function Symfony\Component\String\u;

/**
 *
 *     $ php bin/console app:create-user
 */
class ComandoCrearUsuario extends Command
{
    protected static $defaultName = 'app:create-user';
    //EntityManager: em
    private $em;
    private $repoUsuario;
    private $passEncoderUsuario;

    public function __construct(
        EntityManagerInterface $em, 
        UserPasswordEncoderInterface $passEncoderUsuario,
        UsuarioRepository $repoUsuario
    ){
        $this->em = $em;
        $this->passEncoderUsuario = $passEncoderUsuario;
        $this->repoUsuario = $repoUsuario;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Este comando permite crear un usuario')
            ->setHelp('Este comando permite crear un usuario')
            ->addArgument('email', 
            InputArgument::REQUIRED, 
            'email'
            )
            ->addArgument('password', 
            InputArgument::REQUIRED, 
            'La contraseña del nuevo usuario'
            )
            ->addArgument('firmaUsuario',
             InputArgument::REQUIRED, 
             'El nombre con el que le verán los demás usuarios'
             )
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Crea usuario como admin')
            ;
    }
    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $output ->writeln('<fg=white;bg=cyan> Creador de usuario</>');
        
        $email = $input->getArgument('email');
        $plainPass = $input->getArgument('password');
        $firmaUsuario = $input->getArgument('firmaUsuario');
        $isAdmin = $input->getOption('admin');

        $usuario = $this->repoUsuario->findOneByEmail($email);
        if(!empty($usuario)) {
            $output->writeln('<error>El email introducido se corresponde con un usuario existente</error>');
            return 0;
        }
         
        // create the user and encode its password
        $usuario = new Usuario();
        $usuario->setEmail($email);
        $contraseña = $this->passEncoderUsuario->encodePassword($usuario,$plainPass);
        $usuario->setPassword($contraseña);
        $usuario->setFirmaUsuario($firmaUsuario);
        $usuario->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);
        $this->em->persist($usuario);
        $this->em->flush();

        $output->writeln('<fg=white;bg=green>El usuario fue creado</>');
        return 1;
    }
}