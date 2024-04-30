<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\LieuxVentes;
use App\Entity\JourneeTravail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class JourneeTravailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jourDeTravail', null, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Jour de travail*',
                // 'data' => new \DateTime(), // Définir la date par défaut sur la date du jour
                
                'attr' => ['max' => (new \DateTime())->format('Y-m-d')],
            ])
            ->add('statut', ChoiceType::class,[
                "choices"       =>  [
                    'en-cours'   => 'en-cours',
                    'clos'   => 'clos',
                ],
                "label"     =>"Etat*",
                "required"  =>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JourneeTravail::class,
        ]);
    }
}
