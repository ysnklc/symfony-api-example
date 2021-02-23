<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[]    findAll()
 * @method Subscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriberRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Subscriber::class);
        $this->manager = $manager;
    }

    public function addSubscriber($firstName, $lastName, $email, $phoneNumber, $city)
    {
        $subscriber = new Subscriber();

        $subscriber
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPhone($phoneNumber)
            ->setCity($city);

        $this->manager->persist($subscriber);
        $this->manager->flush();
    }

    public function updateSubscriber(Subscriber $subscriber): Subscriber
    {
        $this->manager->persist($subscriber);
        $this->manager->flush();

        return $subscriber;
    }

    public function removeSubscriber(Subscriber $subscriber)
    {
        $this->manager->remove($subscriber);
        $this->manager->flush();
    }
}
