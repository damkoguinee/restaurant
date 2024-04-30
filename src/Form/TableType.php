<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Table;
use App\Entity\LieuxVentes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class TableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                "label"     =>"Nom de la table*",
                'required' => true,
                "constraints"       =>[
                    New Length([
                        "max"           =>  100,
                        "maxMessage"   =>  "Le nom ne doit pas depassé 100 caractères"
                    ]),
                    new NotBlank(["message" => "la boisson ne peut pas être vide"])
                ]
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nom',
                'required' => true,
                'placeholder' => "selectionnez une salle",
                'label' => 'Salle*'
            ])
            ->add('capacite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Table::class,
        ]);
    }
}
