<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MainController extends AbstractController
{
//    NOM DE LA ROUTE

    #[Route('/l_ombre_de_toi/accueil', name: 'app_accueil')]
    public function base(): Response
    {

//        RETOURNE LA VUE

        return $this->render('main/accueil.html.twig');

    }

    #[Route('/l_ombre_de_toi/tpb', name: 'app_tpb')]
    public function tpb(): Response
    {

        return $this->render('main/tpb.html.twig');

    }

    #[Route('/l_ombre_de_toi/ressources', name: 'app_ressources')]
    public function ressources(): Response
    {

        return $this->render('main/ressources.html.twig');

    }

    #[Route('/l_ombre_de_toi/discussion', name: 'app_discussion')]
    public function discussion(): Response
    {

        return $this->render('main/discussion.html.twig');

    }

    #[Route('/l_ombre_de_toi/profil', name: 'app_profil')]
    public function profil(): Response
    {

        return $this->render('main/profil.html.twig');

    }
}
