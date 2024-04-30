<?php

namespace App\Form;

use App\Entity\Boisson;
use App\Entity\Service;
use App\Entity\CategorieBoisson;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class BoissonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie', EntityType::class, [
                'class' => CategorieBoisson::class,
                'choice_label' => 'nom',
                "placeholder"       =>"Selectionnez la catégorie de boisson",
                'label' => 'Catgorie de boisson*'
            ])
            // ->add('typeBoisson', ChoiceType::class,[
            //     "choices"       =>  [
            //         'bouteille'   => 'bouteille',
            //         'verre'   => 'verre',
            //     ],
            //     "label"     =>"Type*",
            //     "required"  =>true,
            //     'expanded' => true,   // Set to true to allow multiple choices
            //     'multiple' => true,   // Set to true to allow multiple choices
            // ])

            
            ->add('nom', null, [
                "label"     =>"Nom de la boisson*",
                'required' => true,
                "constraints"       =>[
                    New Length([
                        "max"           =>  100,
                        "maxMessage"   =>  "Le nom ne doit pas depassé 100 caractères"
                    ]),
                    new NotBlank(["message" => "la boisson ne peut pas être vide"])
                ]
            ])
            ->add('description', null, [
                "label"     =>"Ajouter une description",
                'required' => false,
                "constraints"       =>[
                    New Length([
                        "max"           =>  255,
                        "maxMessage"   =>  "La description ne doit pas depassé 255 caractères"
                    ])
                ]
            ])
            ->add('prixVente', NumberType::class, [
                'label' => 'Prix de vente*',
                'scale' => 0,
                "required"  =>true,
            ])
            ->add('volume', NumberType::class, [
                'label' => 'Volume',
                'scale' => 0,
                "required"  =>false,
            ])
            ->add('unite', null, [
                "label"     =>"Unité",
                'required' => false,
                "constraints"       =>[
                    New Length([
                        "max"           =>  5,
                        "maxMessage"   =>  "L'unité ne doit pas depassé 5 caractères"
                    ])
                ]
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'nom',
                "placeholder"       =>"Selectionner le lieu de service",
                'label' => 'Lieu de service*'
            ])
            ->add("image", FileType::class, [
                "mapped"        =>  false,
                "required"      => false,
                "constraints"   => [
                    new File([
                        "mimeTypes" => [ "image/jpeg", "image/gif", "image/png" ],
                        "mimeTypesMessage" => "Formats acceptés : gif, jpg, png",
                        "maxSize" => "2048k",
                        "maxSizeMessage" => "Taille maximale du fichier : 2 Mo"
                    ])
                ],
                "help" => "Formats autorisés : images jpg, png ou gif"
            ]);
            if (!empty($options['data'])) {
                $builder->add('typeBoisson', ChoiceType::class, [
                    "choices" => [
                        'bouteille' => 'bouteille',
                        'verre' => 'verre',
                    ],
                    "label" => "Type*",
                    "required" => true,
                ]);
            }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boisson::class,
        ]);
    }
}
