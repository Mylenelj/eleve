<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Eleve;
use Doctrine\ORM\EntityManagerInterface;

class EleveController extends AbstractController
{
    #[Route('/', name: 'eleves')]
    public function index(): Response
    {
        //on appelle le repository Eleve (c'est que pour les select (pour cheercher les données))
        $repo = $this->getDoctrine()->getRepository(Eleve::class);

        //Liste des eleves
        $eleves = $repo->findAll();

        //Appelle de la vue et envoie des variables
        return $this->render('eleve/index.html.twig', [
            'eleves' => $eleves,
            'title' => "Affichage des eleves"
        ]);
    }

    #[Route('/newEleve', name: 'nouvelEleve')]
    #[Route('/majEleve/{id}', name: 'modifEleve')]
    public function new(Request $request, EntityManagerInterface $manager, Eleve $eleve = null): Response
    {
        if (!$eleve) {
            $eleve = new Eleve();
        }

        $form = $this->createFormBuilder($eleve)
            ->add('nom')
            ->add('prenom')
            ->add('dateNaissance')
            ->add('moyenne')
            ->add('appreciation')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($eleve);
            $manager->flush();
            return $this->redirectToRoute('eleves');
        }

        return $this->render('eleve/majEleve.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView()
        ]);
    }
}
