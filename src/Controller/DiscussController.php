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
            return $this->redirectToRoute('discussion_publication_view', [
                'id' => $newArticle->getId(),
            ]);

        }

        return $this->render('discuss/new_publication.html.twig', [
            'new_publication_form' => $form->createView(),
        ]);
    }

    // Contrôleur de la page qui liste les articles

    #[Route('/discussion', name: 'discussion_')]
    public function publicationList(\Doctrine\Persistence\ManagerRegistry $doctrine): Response
    {

        // Récupération du repository des articles
        $articleRepo = $doctrine->getRepository(Article::class);

        $articles = $articleRepo->findAll();


        // Envoi des articles à la vue
        return $this->render('main/discussion.html.twig', [
            'articles' => $articles,
        ]);

    }

    #[Route('/publication/{id}', name: 'publication_view')]
    public function publicationView(Article $article): Response
    {

        return $this->render('discuss/publication_view.html.twig', [
            'article' => $article,
        ]);

    }

    #[Route('/publication/suppression/{id}/', name: 'publication_delete', priority: 10)]
    #[IsGranted('ROLE_ADMIN')]
    public function publicationDelete(Article $article, \Doctrine\Persistence\ManagerRegistry $doctrine, \Symfony\Component\HttpFoundation\Request $request): Response
    {

            $em = $doctrine->getManager();
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', 'La publication a été supprimée avec succès !');


        return $this->redirectToRoute('discussion_discussion_');

    }

    // Contrôleur de la page admin servant à modifier un artciel existant via son id passé dans l'URL

    #[Route('/publication/modifier/{id}/', name: 'publication_edit', priority: 10)]
    #[IsGranted('ROLE_ADMIN')]
    public function publicationEdit(Article $article, \Symfony\Component\HttpFoundation\Request $request, \Doctrine\Persistence\ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(NewPublicationFormType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $doctrine->getManager();
            $em->flush();

            $this->addFlash('success','Publication modifiée avec succès !');

            return $this->redirectToRoute('discussion_publication_view', [
                'id' => $article->getId(),
            ]);

        }

        return $this->render('discuss/publication_edit.html.twig',[
            'edit_form' => $form->createView(),
        ]);
    }

}
