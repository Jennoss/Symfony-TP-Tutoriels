<?php

namespace App\Form;

use App\Entity\Chapitre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChapitreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du chapitre',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un titre pour le chapitre',
                    ]),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le contenu du chapitre',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 15
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapitre::class,
        ]);
    }
}