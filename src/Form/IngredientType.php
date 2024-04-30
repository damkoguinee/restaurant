<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                "label"     =>"Nom de l'ingrédient*",
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
                'label' => 'Prix de vente',
                'scale' => 0,
                "required"  =>false,
            ])

            ->add('uniteMesure', null, [
                "label"     =>"Unité",
                'required' => false,
                "constraints"       =>[
                    New Length([
                        "max"           =>  10,
                        "maxMessage"   =>  "L'unité ne doit pas depassé 5 caractères"
                    ])
                ]
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
