<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (ProgramFixtures::PROGRAMS as $program) {
            for ($season['Number'] = 1; $season['Number'] <= 5; $season['Number']++) {
                for ($i = 1; $i <= 10; $i++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->sentence());
                    $episode->setNumber($i);
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    $episode->setSeason($this->getReference('program_' . $program['Title'] . 'season_' . $season['Number']));

                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }
    public function getDependencies()
    {

        return [
            ProgramFixtures::class,
            SeasonFixtures::class,
        ];
    }
}
