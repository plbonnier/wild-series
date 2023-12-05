<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Actor;
use App\DataFixtures\ProgramFixtures;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
            for($i = 1; $i <= 10; $i++) {
                $actor = new Actor();
                $actor->setName($faker->name());
                $manager->persist($actor);

                $programs = $manager->getRepository(Program::class)->findAll();
            $randomPrograms = $faker->randomElements($programs, 3);

            foreach ($randomPrograms as $program) {
                $actor->addProgram($program);
            }

            }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}