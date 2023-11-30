<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        ['Title' => 'Robin des Bois - Prince des voleurs', 'Synopsis' => 'En 1193, le Roi d\'Angleterre, Richard Coeur de Lion, est retenu captif par les Autrichiens.', 'Category' => 'category_Aventure' ],
        ['Title' => '2012', 'Synopsis' => 'Un cataclysme mondial prédit par les mayas menace l\'humanité entière. Jackson Curtis, doit sauver sa famille et trouver un havre de sécurité.', 'Category' => 'category_Action' ],
        ['Title' => 'Alice au pays des merveilles', 'Synopsis' => 'Désormais plus âgée, Alice retourne au pays des Merveilles. Avec le Chapelier Fou et tout ses amis, elle tentera de mettre fin au règne de la terrible reine rouge.', 'Category' => 'category_Fantastique' ],
        ['Title' => 'All the Boys Love Mandy Lane', 'Synopsis' => 'Mandy Lane. Belle. Pure et innocente. Une reine lycéenne en attente d\'être couronnée. Depuis le début de l\'année scolaire, tous les garçons ont cherché à la conquérir.', 'Category' => 'category_Horreur' ],
        ['Title' => 'Ping Pong', 'Synopsis' => 'Peco et Smile sont deux amis d\'enfance. ', 'Category' => 'category_Animation' ],
        ['Title' => 'Attaque', 'Synopsis' => 'Après la destruction de sa ville natale et la mort de sa mère, le jeune Eren Yeager promet de purger la terre des géants humanoïdes appelés Titans qui menacent l\'humanité toute entière.', 'Category' => 'category_Animation' ],
        ['Title' => 'Arcane', 'Synopsis' => 'La série a lieu dans le monde de Runeterra, l\'univers fictif de Riot Games dans lequel prennent également place, notamment, les jeux League of Legends, Legends of Runeterra et League of Legends: Wild Rift', 'Category' => 'category_Animation' ],
    ];
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        foreach (self::PROGRAMS as $programLine) {
        $program = new Program();
        $program->setTitle($programLine['Title']);
        $program->setSynopsis($programLine['Synopsis']);
        $program->setCategory($this->getReference($programLine['Category']));
        $manager->persist($program);
        $this->addReference('program_' . $programLine['Title'], $program);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
