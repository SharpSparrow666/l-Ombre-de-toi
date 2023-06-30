<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
//    NOM DE LA ROUTE

    #[Route('/acceuil', name: 'app_acceuil')]
    public function index(): Response
    {

//        RETOURNE LA VUE

        return $this->render('main/index.html.twig');

    }

    #[Route('/tpb', name: 'app_tpb')]
    public function tpb(): Response
    {

        return $this->render('main/tpb.html.twig');

    }

    #[Route('/ressources', name: 'app_ressources')]
    public function ressources(): Response
    {

        return $this->render('main/ressources.html.twig');

    }

    #[Route('/discussion', name: 'app_discussion')]
    public function discussion(): Response
    {

        return $this->render('main/discussion.html.twig');

    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {

        return $this->render('main/profil.html.twig');

    }

}
