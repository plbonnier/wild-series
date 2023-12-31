<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Comment;
use App\Form\ProgramType;
use App\Form\SearchProgramType;
use App\Service\ProgramDuration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(Request $request, ProgramRepository $programRepository, RequestStack $requestStack): Response
    {
        $form=$this->createForm(SearchProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findLikeNameProgramOrActor($search);
        } else {
            $programs = $programRepository->findAll();
        }
        $session = $requestStack->getSession();
        // $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
            'form' => $form,
        ]);
    }

    #[ROUTE('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, MailerInterface $mailer, ProgramRepository $programRepository): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser());
            $entityManager->persist($program);
            $entityManager->flush();
            // $programRepository->save($program, true);
            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            
            $this->addFlash('success', 'The new program has been created');


            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration2): Response
    {
        // if (!$program) {
        //     throw $this->createNotFoundException(
        //         'No program with id : ' . $program . ' found in program\'s table.'
        //     );
        // }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'slug' => $program->getSlug(),
            'programDuration2' => $programDuration2->calculate($program)
        ]);
    }

    #[Route('/{slug}/season/{season}', name: 'season_show')]
    public function showSeason(Program $program, Season $season, ProgramDuration $programDuration2): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'slug' => $program->getSlug(),
            'season' => $season,
            'programDuration2' => $programDuration2->calculate($program)
        ]);
    }

    #[Route('/{slug}/season/{seasonId}/episode/{episodeId}', name: 'episode_show')] 
    public function showEpisode(
        #[MapEntity(mapping: ['slug' => 'slug'])] Program $program,
        #[MapEntity(mapping: ['seasonId' => 'id'])] Season $season,
        #[MapEntity(mapping: ['episodeId' => 'id'])] Episode $episode,
        ProgramDuration $programDuration,
        // Comment $comment
    ) {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'slug' => $program->getSlug(),
            'season' => $season,
            'episode' => $episode,
            'programDuration' => $programDuration->calculate($program),
            // 'comment' => $comment
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
            $this->addFlash('danger', 'The program has been deleted');
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    // #[Route('/', name: 'app_home')]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $program->getOwner() && !$this->isGranted('ROLE_ADMIN')) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Only the owner can edit the program!');
        }
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
}
}