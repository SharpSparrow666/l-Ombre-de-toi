<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Form\NewPublicationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Préfixe de la route et du nom de toutes les pages de la partie blog du site
 */
#[Route('/discussion', name: 'discuss_')]
class BlogController extends AbstractController
{

    /**
     * Contrôleur de la page permettant de créer un nouvel article
     */
    #[Route('/new', name: 'publication_new')]
    public function publicationNew(Request $request, ManagerRegistry $doctrine): Response
    {
        // Création d'un nouvel Article vide
        $newArticle = new Article();

        $form = $this->createForm(NewPublicationFormType::class, $newArticle);

        //Liaison des données post au formulaire
        $form->handleRequest($request);

        // Si le formulaire sans erreur
        if ($form->isSubmitted() && $form->isValid()) {

            // Hydratation
            $newArticle
                ->setPublicationDate(new \DateTime())
                ->setAuthor($this->getUser());

            //Sauvegarde en BDD grâce au manager des entités
            $em = $doctrine->getManager();
            $em->persist($newArticle);
            $em->flush();

            //Message flash de succès
            $this->addFlash('success', 'Article publié avec succès !');

            return $this->redirectToRoute('app_discussion', [
                'slug' => $newArticle->getSlug(),
            ]);
        }

        return $this->render('main/discussion.html.twig', [
            'new_publication_form' => $form->createView(),
        ]);
    }

    // Contrôleur de la page qui liste les articles

    #[Route('/l_ombre_de_toi/discussion', name: 'publication_list')]
    public function publicationList(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {
        $requestedPage = $request->query->getInt('page', 1);

        if ($requestedPage < 1) {

            throw new NotFoundHttpException();

        }

        $em = $doctrine->getManager();

        $query = $em->createQuery('SELECT a FROM App\Entity\Article a ORDER BY a.publicationDate DESC');

        $articles = $paginator->paginate(
            $query,
            $requestedPage,
            10
        );

        return $this->render('main/discussion.html.twig', [
            'articles' => $articles,
        ]);
    }
}
