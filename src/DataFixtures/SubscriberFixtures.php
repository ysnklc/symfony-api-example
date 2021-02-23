<?php

namespace App\DataFixtures;

use App\Entity\Subscriber;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SubscriberFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $subscriber = new Subscriber();
            $subscriber->setFirstName($faker->firstName);
            $subscriber->setLastName($faker->lastName);
            $subscriber->setEmail($faker->email);
            $subscriber->setPhone($faker->phoneNumber);
            $subscriber->setCity($this->getReference(CityFixtures::CITY_REFERENCE . '-' . rand(0,2)));
            $manager->persist($subscriber);
        }

        $manager->flush();
    }
}
