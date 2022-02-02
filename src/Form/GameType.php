<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'label'  => 'Date du match : ',
            ])
            ->add('fiveLink', TextType::class, [
                'label' => 'Lien vers le match : ',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Joueurs participants : ',
                'by_reference' => false,
                'choice_label' => 'pseudo',
                'multiple' => true,
                'expanded' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
