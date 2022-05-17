<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Session;
use App\Entity\SessionProgramme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * @Route("/session/ajouter/stagiaire/{idSe}/{idSt}", name="ajouter_stagiaire_session")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les privilèges nécessaires")
     * @ParamConverter("session", options={"mapping": {"idSe" : "id"}})
     * @ParamConverter("stagiaire", options={"mapping": {"idSt" : "id"}})
     */
    public function ajouterStagiaire(Session $session, ManagerRegistry $doctrine, Stagiaire $stagiaire)
    {
        $entityManager = $doctrine->getManager();
        $session->addStagiaire($stagiaire);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('detail_session',
            ['id' => $session->getId()]
        );
    }

    /**
     * @Route("/session/supprimer/{idSe}/{idSt}", name="supprimer_stagiaire_session")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les privilèges nécessaires")
     * @ParamConverter("session", options={"mapping" = {"idSe" : "id"}})
     * @ParamConverter("stagiaire", options={"mapping" = {"idSt" : "id"}})
     */
    public function supprimerStagiaire(Session $session, ManagerRegistry $doctrine, Stagiaire $stagiaire): Response
    {
        $entityManager = $doctrine->getManager();
        $session->removeStagiaire($stagiaire);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('detail_session', ['id' => $session->getId()]);
    }

    // Tester le lien si il y a un truc qui bug

    /**
     * @Route("/session", name="app_session")
     */
    public function index(): Response
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }

    /**
     * @Route("/session/ajouter", name="ajouter_session")
     * @Route("/session/maj/{id}", name="maj_session")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les privilèges nécessaires")
     */
    public function ajouter(ManagerRegistry $doctrine, Session $session = null, Request $request): Response
    {
        if (!$session) {
            $session = new Session();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/ajouter.html.twig', [
        'ajouterSession' => $form->createView(),
    ]);
    }

    /**
     * @Route("/session/ajouter_cours/{idSession}/{idCours}", name="ajouter_cours_session")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les privilèges nécessaires")
     *
     * @ParamConverter("session", options={"mapping" = {"idSession" : "id"}})
     * @ParamConverter("cours", options={"mapping" = {"idCours" : "id"}})
     */
    public function ajouterCours(ManagerRegistry $doctrine, Session $session, Cours $cours, Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        $programme = new SessionProgramme();

        $nbJour = $request->request->get('nbJoursCours');

        $programme->setNbJoursCours($nbJour);
        $programme->setSession($session);
        $programme->setCours($cours);
        $session->addSessionProgramme($programme);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('detail_session',
        ['id' => $session->getId()]);
    }

    /**
     * @Route("/session/supprimer_cours/{idSession}/{idCours}", name="supprimer_cours_session")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les privilèges nécessaires")
     *
     * @ParamConverter("session", options={"mapping" = {"idSession" : "id"}})
     * @ParamConverter("cours", options={"mapping" = {"idCours" : "id"}})
     */
    public function SupprimerCours(Session $session, ManagerRegistry $doctrine, SessionProgramme $sessionProgramme): Response
    {
        $entityManager = $doctrine->getManager();
        $session->removeSessionProgramme($sessionProgramme);
        $entityManager->remove($sessionProgramme);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('detail_session', [
            'id' => $session->getId(),
    ]);
    }

    /**
     * @Route("/session/detail/{id}", name="detail_session")
     */
    public function detail(ManagerRegistry $doctrine, Session $session, StagiaireRepository $sta, int $id, SessionRepository $repo): Response
    {
        $stagiaires = $doctrine->getRepository(Stagiaire::class)->findAll();

        $nonInscrits = $sta->getNonInscrits($session->getId());
        $programmesNonInscrits = $repo->getCoursNonProgrammes($session->getId());

        return $this->render('session/index.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'nonInscrits' => $nonInscrits,
            'coursNonProgrammes' => $programmesNonInscrits,
        ]);
    }

    // ajouter un stagiaire dans une session --> addStagiaire(Stagiaire $stagiaire)
    // prendre logique prog non inscrit
}
