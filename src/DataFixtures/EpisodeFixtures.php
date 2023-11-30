<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const EPISODES = [
        ['Title' => 'To You, in 2000 Years: The Fall of Shiganshina, Part 1', 'Number' => '1', 'Synopsis' => 'After 100 years of peace, humanity is suddenly reminded of the terror of being at the Titans\' mercy.', 'Season' => 'season_1' ],
        ['Title' => 'That Day: The Fall of Shiganshina, Part 2', 'Number' => '2', 'Synopsis' => 'After the Titans break through the wall, the citizens of Shiganshina must run for their lives. Those that do make it to safety find a harsh life waiting for them, however.', 'Season' => 'season_1' ],
        ['Title' => 'A Dim Light Amid Despair: Humanity\'s Comeback, Part 1', 'Number' => '3', 'Synopsis' => 'Eren begins his training with the Cadet Corps, but questions about his painful past overwhelm him. When he struggles with a maneuvering exercise, Berholt and Reiner offer kindly advice.', 'Season' => 'season_1' ],
        ['Title' => 'The Night of the Closing Ceremony: Humanity\'s Comeback, Part 2', 'Number' => '4', 'Synopsis' => 'Annie proves her skill in a sparring session, Jan dreams of serving alongside the King, and graduation day brings shocking revelations - along with a sudden outbreak of violence.', 'Season' => 'season_1' ],
    ];
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        foreach (self::EPISODES as $episodeLine) {
            $episode = new Episode();
            $episode->setTitle($episodeLine['Title']);
            $episode->setNumber($episodeLine['Number']);
            $episode->setSynopsis($episodeLine['Synopsis']);
            $episode->setSeason($this->getReference($episodeLine['Season']));
            $manager->persist($episode);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            SeasonFixtures::class,
        ];
    }
}
