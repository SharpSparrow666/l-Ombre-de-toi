<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewPublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title', TextType::class, [
            new Length([
                'min' => 5,
                'max' => 255,
                'minMessage' => 'Votre titre doit contenir au moins 5 caractères',
                'maxMessage' => 'Votre titre doit contenir au maximum 255 caractères',
        ]),
            ])
            ->add('content', TextType::class, [
            'label' => 'Contenu',
                'constraints' => [
            new NotBlank([
                'message' => 'Merci de remplir du contenu',
            ]),
            new Length([
                 'min' => 15,
                  'max' => 1500,
                  'minMessage' => 'Votre contenu doit contenir au moins 15 caractères',
                  'maxMessage' => 'Votre contenu doit contenir au maximum 1500 caractères',
            ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
