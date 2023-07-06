<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo',TextType::class, [
                'attr' => [
                    'class' => 'w-100 border border-success border-3',
                ],
                'label' => 'Pseudo :'
            ])
            ->add('age',TextType::class, [
                'attr' => [
                    'class' => 'w-100 border border-success border-3',
                ],
                'label' => 'Age :'
            ])
            ->add('email',EmailType::class, [
            'attr' => [
                    'class' => 'w-100 border border-success border-3',
                ],
            'label' => 'Email',
            'constraints' => [
                new NotBlank([
                    'message' => 'Merci de renseigner une adresse email',
                ]),
                new Email([
                    'message' => 'L\'adresse mail {{ value }} n\'est pas une adresse valide',
                    ]),
                ],
                'label' => 'E-mail :'
            ])
            ->add('plainPassword',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe ne correspond pas à sa confirmation',
                'first_options' => [
                    'attr' => [
                        'class' => 'w-100 border border-success border-3'
                    ],
                    'label' => 'Mot de passe :',
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'w-100 border border-success border-3'
                    ],
                    'label' => 'Confirmation :',
                ],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un MDP',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre MDP doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,4096}$/u',
                        'message' => 'Votre mot de passe doit contenir obligatoirement une minuscule, une majuscule, un chiffre et un caractère spécial',
                    ]),
                ],
            ])
            ->add('avatar',FileType::class, [
                'attr' => [
                    'class' => 'w-100 border fw-bold border-success border-3',
                    ],
                'label' => 'Avatar :'
            ])
            ->add('save', SubmitType::class, [ // Ajout d'un champ de type bouton de validation
                'attr' => [
                    'class' => 'w-100 btn btn-success fw-bold border border-success border-3 mt-5',
                ],
                'label' => 'S\'inscrire'    // Texte du bouton
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
