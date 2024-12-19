<?php

namespace App\DataFixtures;

use App\Entity\Matiere;
use App\Entity\Tutoriel;
use App\Entity\Chapitre;
use App\Entity\Commentaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 5; $i++) {
            $matiere = new Matiere();
            $matiere->setName($faker->word())
                    ->setDescription($faker->sentence());
            $manager->persist($matiere);

            for ($j = 1; $j <= 3; $j++) {
                $tutoriel = new Tutoriel();
                $tutoriel->setTitle($faker->sentence())
                         ->setContent($faker->paragraph())
                         ->setCreatedAt($faker->dateTime())
                         ->setUpdatedAt($faker->optional()->dateTime())
                         ->setMatiere($matiere);
                $manager->persist($tutoriel);

                for ($k = 1; $k <= 2; $k++) {
                    $chapitre = new Chapitre();
                    $chapitre->setTitle($faker->sentence())
                             ->setContent($faker->paragraph())
                             ->setTutoriel($tutoriel);
                    $manager->persist($chapitre);

                    for ($l = 1; $l <= 4; $l++) {
                        $commentaire = new Commentaire();
                        $commentaire->setContent($faker->sentence())
                                    ->setCreatedAt($faker->dateTime())
                                    ->setChapitre($chapitre);
                        $manager->persist($commentaire);
                    }
                }
            }
        }

        $manager->flush();
    }
}

