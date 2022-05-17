<?php

namespace App\Controller;

use App\Entity\SessionProgramme;
use App\Form\SessionProgrammeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionProgrammeController extends AbstractController
{
    /**
     * @Route("/session/programme", name="app_session_programme")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $session_cours = $doctrine->getRepository(SessionProgramme::class)->findBy(['cours_id']);

        return $this->render('session/programme/index.html.twig', [
            'session_programme' => $session_cours,
        ]);
    }

    /**
     * @Route("/session/programme/ajouter", name="ajouter_programme_session")
     */
    public function add(ManagerRegistry $doctrine, SessionProgramme $sessionProgramme = null, Request $request): Response
    {
        if (!$sessionProgramme) {
            $sessionProgramme = new SessionProgramme();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(SessionProgrammeType::class, $sessionProgramme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sessionProgramme = $form->getData();
            $entityManager->persist($sessionProgramme);
            $entityManager->flush();

            return $this->redirectToRoute('app_session_programme');
        }

        return $this->render('session_programme/ajouter.html.twig', [
        'sessionProgramme' => $form->createView(),
    ]);
    }
}
