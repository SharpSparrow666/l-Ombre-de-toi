<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $photo = $form->get('photo')->getData();

                // Création d'un nouveau nom aléatoire pour la photo avec son extension (récupérée via la méthode guessExtension() )
                $newFileName = md5(time() . rand() . uniqid() ) . '.' . $photo->guessExtension();

                // Déplacement de la photo dans le dossier que l'on avait paramétré dans le fichier services.yaml, avec le nouveau nom qu'on lui a généré
                $photo->move(
                    $this->getParameter('app.photos.directory'),     // Emplacement de sauvegarde du fichier
                    $newFileName    // Nouveau nom du fichier
                );

                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                if(isset($_POST['honeypot']) && empty($_POST['honeypot'])){

                $entityManager->persist($user);
                $entityManager->flush();

                }

                // do anything else you need here, like send an email

                return $this->redirectToRoute('app_accueil');
            }

            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);

        }
}
