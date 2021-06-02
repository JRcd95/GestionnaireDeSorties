<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomSortie', TextType::class, [
                'label' => 'Nom de la sortie :'
            ])

            ->add('dateHeureDebut', DateTimeType::class,[
                'label' => 'Date et heure de la sortie :',
                'widget' => 'single_text'])

            ->add('duree', TimeType::class,[
                'label' => 'Durée :',
                'widget' => 'single_text'])

            ->add('dateLimiteInscription', DateTimeType::class,[
                'label' => 'Date limite d\'inscription :',
                'widget' => 'single_text'])

            ->add('nbInscriptionMax', IntegerType::class,[
                'label' => 'Nombre de places :'])

            ->add('infosSortie', TextType::class, [
                'label' => 'Description et infos :'
            ])

            ->add('lieu', EntityType::class, [
                'label' => 'Lieu :',
                'class' => Lieu::class

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
