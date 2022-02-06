<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Statistique;
use App\Entity\User;
use App\Repository\GameRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatByPlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('game', EntityType::class, [
                'class' => Game::class,
                'label' => 'Choisis le match ',
                'choice_label' => 'dateGame',
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function (GameRepository $game) {
                    return $game ->createQueryBuilder('g')->orderBy('g.date', 'DESC');
            }
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Choisis le joueur ',
                'by_reference' => false,
                'choice_label' => 'pseudo',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('passesDecisives')
            ->add('buts')
            ->add('resultat', ChoiceType::class, [
                'choices' => [
                    'Victoire' => 'V',
                    'Défaite' => 'D',
                    'Nul' => 'N',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Statistique::class,
        ]);
    }
}
