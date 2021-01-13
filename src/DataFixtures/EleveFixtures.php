<?php

namespace App\DataFixtures;

use App\Entity\Eleve;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker;



class EleveFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i < 10; $i++) {
            $eleve[$i] = new Eleve();
            $eleve[$i]->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setDateNaissance($faker->dateTimeBetween($startDate = '-30 years', $endDate = '-29 years', $timezone = null))
                ->setAppreciation($faker->word)
                ->setMoyenne(random_int(10, 20));

            $manager->persist($eleve[$i]);
        }
        $manager->flush();
    }
}
