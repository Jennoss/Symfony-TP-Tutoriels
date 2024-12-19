<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Tutoriel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TutorielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du tutoriel',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un titre pour le tutoriel',
                    ]),
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le contenu du tutoriel',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 10
                ]
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'name',
                'label' => 'Matière',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une matière',
                    ]),
                ],
                'attr' => ['class' => 'form-select']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tutoriel::class,
        ]);
    }
}