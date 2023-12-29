<?php

namespace App\Twig\Components;

use App\Entity\Program;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent()]
final class WatchList
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public Program $program; 

    public function __construct(
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager
    ) {}
    
    #[LiveAction]
    public function toggle(): void
    {
     /** @var User $user */
        $user = $this->security->getUser();
        if ($user->getWatchlist()->contains($this->program)) {
            $user->removeFromWatchlist($this->program);
        }else {
            $user->addToWatchlist($this->program);
        }
        $this->entityManager->flush();
    }
}
