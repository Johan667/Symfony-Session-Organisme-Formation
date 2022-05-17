<?php

namespace App\Controller;

use App\Repository\FormateurRepository;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ManagerRegistry $doctrine, StagiaireRepository $sr, FormationRepository $fr, FormateurRepository $ffr, SessionRepository $ss): Response
    {
        $entityManager = $doctrine->getManager();
        $stagiaires = $sr->findAll();
        $formations = $fr->findAll();
        $formateurs = $ffr->findAll();
        $sessions = $ss->findAll();
        $sessionPassees = $ss->AfficherSessionPasses();
        $sessionFutures = $ss->AfficherSessionFutures();
        $sessionsNow = $ss->AfficherSessionNow();

        return $this->render('home/index.html.twig', [
            'stagiaires' => $stagiaires,
            'formations' => $formations,
            'formateurs' => $formateurs,
            'sessions' => $sessions,
            'sessionsPassees' => $sessionPassees,
            'sessionsFutures' => $sessionFutures,
            'sessionsNow' => $sessionsNow,
        ]);
    }

    /**
     * @Route("/account", name="account")
     */
    public function compte(UserRepository $users): Response
    {
        $user = $users->findAll();
        // requete dql dans le repository qui ressort les formation selon dates. 1par type de session /3.

        return $this->render('home/account.html.twig', [
            'users' => $user,
        ]);
    }
}
