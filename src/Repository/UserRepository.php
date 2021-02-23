<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $manager;
    private $encoder;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
        $this->encoder = $encoder;
    }

    public function addUser($firstName, $lastName, $username, $email, $password)
    {
        $user = new User();

        $user
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($this->encoder->encodePassword($user, $password));

        $this->manager->persist($user);
        $this->manager->flush();
    }
}
