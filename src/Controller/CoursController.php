<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    /**
     * @Route("/cours", name="app_cours")
     */ // AJOUTER OU SUPPR DES MODULES.

    public function index(CoursRepository $cours): Response
    {
        $cours = $cours->findAll();

        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
        ]);
    }

    /**
     * @Route("/cours/ajouter", name="ajouter_cours")
     * @Route("/cours/maj/{id}", name="maj_cours")
     */
    public function ajouterCours(ManagerRegistry $doctrine, Cours $cour = null, Request $request): Response
    {
        if (!$cour) {
            $cour = new Cours();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cours = $form->getData();
            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('app_cours');
        }

        return $this->render('cours/ajouter.html.twig', [
       'ajouterCours' => $form->createView(),
   ]);
    }
}
