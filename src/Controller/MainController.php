<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditAvatarFormType;
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

    #[Route('/l_ombre_de_toi/profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function profil(): Response
    {

        return $this->render('main/profil.html.twig');

    }

    #[Route('/l_ombre_de_toi/mentions-cgu', name: 'app_mentions-cgu')]
    public function mentions(): Response
    {

        return $this->render('main/mentions-cgu.html.twig');

    }

    #[Route('/changer-photo-de-profil/', name: 'main_edit_avatar')]
    #[IsGranted('ROLE_USER')]
    public function editAvatar(Request $request, ManagerRegistry $doctrine, CacheManager $cacheManager): Response
    {

        $form = $this->createForm(EditAvatarFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $avatar = $form->get('avatar')->getData();

            $connectedUser = $this->getUser();

            $photoLocation = $this->getParameter('app.user.avatar.directory');

            $newFileName = 'user' . $connectedUser->getId() . '.' . $avatar->guessExtension();

            if($connectedUser->getAvatar() != null && file_exists($photoLocation . $connectedUser->getAvatar())){
                $cacheManager->remove('images/profils/' . $connectedUser->getAvatar());
                unlink($photoLocation . $connectedUser->getAvatar());
            }

            $em = $doctrine->getManager();
            $connectedUser->setAvatar($newFileName);
            $em->flush();

            $avatar->move(
                $avatarLocation,
                $newFileName,
            );

            $this->addFlash('success', 'Photo de profil modifiée avec succès !');
            return $this->redirectToRoute('app_profil');

        }

        return $this->render('main/edit_avatar.html.twig', [
            'edit_avatar_form' => $form->createView(),
        ]);
    }
}
