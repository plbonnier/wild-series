<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll();
        return $this->render('actor/index.html.twig', [
            'actors' => $actors
        ]);
    }

    #[Route('/actor/{id}', name: 'actor')]
public function show(Actor $actor): Response{
    if (!$actor) {
        throw $this->createNotFoundException(
            'No actor with id : ' . $actor . ' found in actor\'s table.'
        );
    }
    return $this->render('actor/actor.html.twig', [
        'actor' => $actor,
    ]);
}

}
