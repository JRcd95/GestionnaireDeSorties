<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomSortie', TextType::class, [
                'label' => false
            ])

            ->add('dateHeureDebut', DateTimeType::class,[
                'label' => false,
                'widget' => 'single_text'])

            ->add('duree', IntegerType::class,[
                'label' => false,
            ])

            ->add('dateLimiteInscription', DateTimeType::class,[
                'label' => false,
                'widget' => 'single_text'])

            ->add('nbInscriptionMax', IntegerType::class,[
                'label' => false
            ])

            ->add('infosSortie', TextareaType::class, [
                'label' => false
            ])

            ->add('lieu', EntityType::class, [
                'label' => false,
                'class' => Lieu::class,
                'placeholder' => "--Lieu--"

            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn']
            ])
            ->add('publier', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr' => ['class' => 'btn']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
