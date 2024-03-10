<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la voiture'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('monthly_price', MoneyType::class, ['label' => 'Prix mensuel'])
            ->add('daily_price', MoneyType::class, ['label' => 'Prix journalier'])
            ->add('seats', ChoiceType::class, [
                'label' => 'Nombre de places',
                'choices' => range(1, 9, 1),
            ])
            ->add('manual', ChoiceType::class, [
                'label' => 'Boite de vitesse',
                'choices' => [
                    'Manuelle' => true,
                    'Automatique' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
