<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const SEASONS = [
        ['Number' => '1', 'Year' => '2013', 'Description' => 'Le début, les 1ères attaques', 'Program' => 'program_Attaque' ],
        ['Number' => '2', 'Year' => '2014', 'Description' => 'La suite des attaques', 'Program' => 'program_Attaque' ],
        ['Number' => '3', 'Year' => '2015', 'Description' => 'Encore des attaques', 'Program' => 'program_Attaque' ],
        ['Number' => '4', 'Year' => '2016', 'Description' => 'Ils sont pénibles à toujours attaquer ces géants', 'Program' => 'program_Attaque' ],
    ];
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        foreach (self::SEASONS as $seasonLine) {
        $season = new Season();
        $season->setNumber($seasonLine['Number']);
        $season->setYear($seasonLine['Year']);
        $season->setDescription($seasonLine['Description']);
        $season->setProgram($this->getReference($seasonLine['Program']));
        $manager->persist($season);
        //... set other season's properties
        $this->addReference('season_' . $seasonLine['Number'], $season);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            ProgramFixtures::class,
        ];
    }
}
