<?php

namespace App\Form;

use App\Entity\Caisse;
use App\Entity\Devise;
use App\Entity\Versement;
use App\Entity\ModePaiement;
use App\Entity\PointDeVente;
use App\Repository\CaisseRepository;
use App\Entity\CategorieDecaissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class VersementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $lieu_vente = $options['lieu_vente'];
        $type1 = 'client';
        $type2 = 'client-fournisseur';
        $versement = $options["data"];
        $builder
            ->add('montant', TextType::class, [
                'label' => 'Montant versé*',
                "required"  =>false,
                    'attr' => [
                        'placeholder' => '1 000 000',
                        'onkeyup' => "formatMontant(this)",
                        'style' => 'font-size: 20px; font-weight: bold; ',
                    ]
            ])
            ->add('taux', NumberType::class, [
                'label' => 'Taux',
                'data' => $versement->getTaux() ?? 1,
                'scale' => 2,
                "required"  =>true,
            ])
            ->add('devise', EntityType::class, [
                'class' => Devise::class,
                'choice_label' => 'nomDevise',
                'label' => "Devise"
            ])
            ->add('modePaie', EntityType::class, [
                'class' => ModePaiement::class,
                'choice_label' => 'designation',
                'label' => 'Mode de paie*',
                'placeholder' => "Sélectionnez",
                'required' => true,
            ])
            ->add('numeroPaiement', null, [
                'label' => 'N°Chèque\Bord',
                'required' => false,
                "constraints" => [
                    New Length([
                        "max" => 100,
                        'maxMessage'    => "Le numéro chèque ne doit pas depasser 100 caractères"
                    ])
                ]
            ])
            ->add('banqueCheque', null, [
                'label' => 'Banque Chèque',
                'required' => false,
                "constraints" => [
                    New Length([
                        "max" => 100,
                        'maxMessage'    => "La banque chèque ne doit pas depasser 100 caractères"
                    ])
                ]
            ])
            ->add('compte', EntityType::class, [
                'class' => Caisse::class,
                'choice_label' => 'designation',
                'required' => true,
                'label' => 'Compte de dépôt*',
                'placeholder' => "Sélectionnez un compte",
                'query_builder' => function (CaisseRepository $er) use ($lieu_vente) {
                    return $er->createQueryBuilder('c')
                        ->leftJoin(PointDeVente::class, 'p', 'WITH', 'c.pointDeVente = p.id Or c.pointDeVente is null')
                        ->where('p.lieuVente = :lieu')
                        ->setParameter('lieu', $lieu_vente);

                },

            ])
            ->add('commentaire', null, [
                'label' => 'Commentaire*',
                'required' => false,
                "constraints" => [
                    New Length([
                        "max" => 255,
                        'maxMessage'    => "Le commentaire ne doit pas depasser 255 caractères"
                    ])
                ]
            ])
            ->add('dateVersement', DateType::class, [
                'label' => 'Date de paiement*',
                'widget' => 'single_text',
                'required' => true,
                'data' => new \DateTime(), // Définir la date par défaut sur la date du jour
                
                'attr' => ['max' => (new \DateTime())->format('Y-m-d')], // Limiter la sélection à la date d'aujourd'hui ou antérieure
            ])

            ->add('categorie', EntityType::class, [
                'class' => CategorieDecaissement::class,
                'choice_label' => 'designation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Versement::class,
            'lieu_vente' => null,
        ]);
    }
}
