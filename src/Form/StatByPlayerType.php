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

            ->add('passesDecisives')
            ->add('buts')
            ->add('resultat', ChoiceType::class, [
                'choices' => [
                    'Nul' => 'N',
                    'Défaite' => 'D',
                    'Victoire' => 'V',
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
