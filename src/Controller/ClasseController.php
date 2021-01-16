<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Repository\EleveRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClasseController extends AbstractController
{
    #[Route('/classe', name: 'classe')]
    public function index(): Response
    {
        //on appelle le repository classe (c'est que pour les select (pour chercher les données))
        $repo = $this->getDoctrine()->getRepository(classe::class);

        //Liste des eleves
        $classes = $repo->findAll();

        //Appelle de la vue et envoie des variables
        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
            'title' => "Liste des classes"
        ]);
    }

    #[Route('/classe/new', name: 'newClasse')]
    #[Route('/classe/{id}/update', name: 'updateClasse')]
    public function new(Request $request, EntityManagerInterface $manager, Classe $classe = null): Response
    {
        if (!$classe) {
            $classe = new Classe();
        }

        $form = $this->createFormBuilder($classe)
            ->add('nom')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($classe);
            $manager->flush();
            return $this->redirectToRoute('classe');
        }

        return $this->render('classe/majClasse.html.twig', [
            'classe' => $classe,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/classe/{id}",name="showClasse")
     */

    public function showClasse(ClasseRepository $classeRepository, EleveRepository $eleveRepository, int $id): Response
    {
        $classe = $classeRepository->find($id);
        $eleves = $eleveRepository->findBy([
            'classe' => $id
        ]);
        return $this->render('classe/show.html.twig', [
            'classe' => $classe, 'eleves' => $eleves
        ]);
    }

    /**
     * @Route("classe/{id}/delete", name="deleteClasse")
     */
    public function FunctionSuppression(ClasseRepository $repository, EntityManagerInterface $manager, int $id): Response
    {
        $classe = $repository->find($id);
        $manager->remove($classe);
        $manager->flush();
        return $this->redirectToRoute('classe');
    }
}
