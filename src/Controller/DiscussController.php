<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\NewPublicationFormType;
use http\Client\Request;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/l_ombre_de_toi', name: 'discussion_')]
class DiscussController extends AbstractController
{
    #[Route('/nouvelle-publication/', name: 'new_publication')]
    public function newPublication(\Symfony\Component\HttpFoundation\Request $request, \Doctrine\Persistence\ManagerRegistry $doctrine): Response
    {

        $newArticle = new Article();

        $form = $this->createForm(NewPublicationFormType::class, $newArticle);

//        Liaison des données post au formulaire

        $form->handleRequest($request);

//        Si le formulaire a été envoyé sans erreurs

        if($form->isSubmitted() && $form->isValid()){

//            Hydratation de l'article
            $newArticle
                ->setPublicationDate(new \DateTime())
                ->setAuthor($this->getUser())
                ;

//            sauvegarde en BDD

            $em = $doctrine->getManager();
            $em->persist($newArticle);
            $em->flush();

            $this->addFlash('success', 'Sujet posté avec succès !');

            //TODO: penser à rediriger sur la page qui montre le nouvel article
            return $this->redirectToRoute('app_accueil');

        }

        return $this->render('discuss/new_publication.html.twig', [
            'new_publication_form' => $form->createView(),
        ]);
    }

    // Contrôleur de la page qui liste les articles

    #[Route('/discussion', name: 'discussion_')]
    public function publicationList(): Response
    {

        return $this->render('main/discussion.html.twig');

    }

}
