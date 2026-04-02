<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'label' => 'Note',
                'choices' => [
                    '5 étoiles' => 5,
                    '4 étoiles' => 4,
                    '3 étoiles' => 3,
                    '2 étoiles' => 2,
                    '1 étoile' => 1,
                ],
                'placeholder' => '—',
                'constraints' => [
                    new NotBlank(message: 'Choisissez une note.'),
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Votre avis',
                'attr' => ['rows' => 5, 'maxlength' => 2000],
                'constraints' => [
                    new NotBlank(message: 'Écrivez un commentaire.'),
                    new Length(max: 2000, maxMessage: 'Le commentaire est trop long.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
