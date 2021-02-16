<?php


namespace App\Model\User;


use App\Exceptions\UserAccountException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class UserFacade
{

    /** @var EntityManagerInterface */
    protected $em;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    /** @var RoleHierarchyInterface */
    protected $roleHierarchy;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /**
     * UserFacade constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param EncoderFactoryInterface $encoderFactory
     * @param RoleHierarchyInterface $roleHierarchy
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, EncoderFactoryInterface $encoderFactory, RoleHierarchyInterface $roleHierarchy, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->roleHierarchy = $roleHierarchy;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param UserData $userData
     * @return User
     */
    public function create(UserData $userData): User
    {
        $user = User::create($userData);
        $passwordEncoder = $this->encoderFactory->getEncoder($user);
        $password = $passwordEncoder->encodePassword($userData->password, $user->getSalt());

        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param int $id
     * @param UserData $userData
     * @return User
     * @throws \Exception
     */
    public function edit(int $id, UserData $userData): User
    {
        $user = $this->getById($id);
        $user->edit($userData);
        $this->em->flush();

        return $user;
    }

   
    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        try {
            $user = $this->getById($id);
            $this->em->remove($user);
            $this->em->flush();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param int $id
     * @param bool $onlyEnabled
     * @return User|null
     */
    public function getById(int $id):? User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @return User[]
     */
    public function getAll()
    {
        return $this->userRepository->findAll();
    }


    /**
     * @param string $username
     * @return User|null
     */
    public function findUserByUsername(string $username):? User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }
}
