<?php

namespace App\Form;

use App\Entity\Cocktail;
use App\Entity\TableCommande;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('prixVente')
            ->add('dateCommande', null, [
                'widget' => 'single_text',
            ])
            ->add('dateSaisie', null, [
                'widget' => 'single_text',
            ])
            ->add('tableCommande', EntityType::class, [
                'class' => TableCommande::class,
                'choice_label' => 'id',
            ])
            ->add('saisiePar', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableCommande::class,
        ]);
    }
}
