<?php

namespace App\Form;

use App\Entity\Chicha;
use App\Entity\ChichaRecette;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChichaRecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('chicha', EntityType::class, [
                'class' => Chicha::class,
                'choice_label' => 'id',
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChichaRecette::class,
        ]);
    }
}
