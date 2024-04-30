<?php

namespace App\Form;

use App\Entity\Facturation;
use App\Entity\LieuxVentes;
use App\Entity\ModePaiement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroFacture')
            ->add('numeroTicket')
            ->add('montantTotal')
            ->add('montantPaye')
            ->add('fraisSup')
            ->add('remise')
            ->add('tableCommande')
            ->add('etat')
            ->add('typeVente')
            ->add('commentaire')
            ->add('dateFacturation', null, [
                'widget' => 'single_text',
            ])
            ->add('dateSaisie', null, [
                'widget' => 'single_text',
            ])
            ->add('livraison')
            ->add('client', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('modePayment', EntityType::class, [
                'class' => ModePaiement::class,
                'choice_label' => 'id',
            ])
            ->add('saisiePar', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('lieuVente', EntityType::class, [
                'class' => LieuxVentes::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facturation::class,
        ]);
    }
}
