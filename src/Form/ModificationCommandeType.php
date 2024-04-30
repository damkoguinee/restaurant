<?php

namespace App\Form;

use App\Entity\ModificationCommande;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModificationCommandeType extends AbstractType
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
            ->add('typeVente')
            ->add('commentaire')
            ->add('statut')
            ->add('typeProduct')
            ->add('saisiePar', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('modifierPar', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModificationCommande::class,
        ]);
    }
}
