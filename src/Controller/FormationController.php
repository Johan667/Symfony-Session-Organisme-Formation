<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="app_formation")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $formations = $doctrine->getRepository(Formation::class)->findAll();

        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    /**
     * @Route("/formation/ajouter", name="ajouter_formation")
     * @Route("/formation/maj/{id}", name="maj_formation")
     */
    public function ajouter(ManagerRegistry $doctrine, Formation $formation = null, Request $request): Response
    {
        if (!$formation) {
            $formation = new Formation();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formation = $form->getData();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation');
        }

        return $this->render('formation/ajouter.html.twig', [
        'ajouterFormation' => $form->createView(),
    ]);
    }

    /**
     * @Route("/formation/detail/{id}", name="detail_formation")
     */
    public function detail(Formation $formation): Response
    {
        return $this->render('formation/detail.html.twig', [
            'formation' => $formation,
        ]);
    }
}
