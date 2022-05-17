<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StagiaireController extends AbstractController
{
    /**
     * @Route("/stagiaire", name="app_stagiaire")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $stagiaires = $doctrine->getRepository(Stagiaire::class)->findAll();

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

    /**
     * @Route("/stagiaire/detail/{id}", name="detail_stagiaire")
     */
    public function detail(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/detail.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }

    /**
     * @Route("/stagiaire/ajouter", name="ajouter_stagiaire")
     * @Route("/stagiaire/maj/{id}", name="maj_stagiaire")
     */
    public function ajouter(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, Request $request): Response
    {
        if (!$stagiaire) {
            $stagiaire = new Stagiaire();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stagiaire = $form->getData();
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire');
        }

        return $this->render('stagiaire/ajouter.html.twig', [
        'ajouterStagiaire' => $form->createView(),
    ]);
    }
}
