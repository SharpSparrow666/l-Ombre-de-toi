<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/l_ombre_de_toi/register', name: 'app_register')]
    public function register(Request $request): Response
    {

        $newUser = new User();

        // Création d'un nouveau formulaire à partir de notre formulaire CreateArticleFormType et de notre nouvel article encore vide
        $form = $this->createForm(RegistrationFormType::class, $newUser);

        $form->handleRequest($request);

        return $this->render('registration/register.html.twig', [
        'form'=> $form->createView()
        ]);

    }

}
