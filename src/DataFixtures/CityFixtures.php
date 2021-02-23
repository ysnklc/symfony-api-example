<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CityFixtures extends Fixture
{
    public const CITY_REFERENCE = 'subscriber-city';

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 3; $i++) {
            $city = new City();
            $city->setName($faker->city);
            $manager->persist($city);
            $manager->flush();

            $this->addReference(self::CITY_REFERENCE . '-' . $i, $city);
        }
    }
}
