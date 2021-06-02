<?php

namespace App\Form;

use App\Entity\Campus;
use App\Search\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campusSearch', EntityType::class, [
                'label' => 'Campus :',
                'required' => false,
                'class' => Campus::class,
                'expanded' => true,
                'multiple' => true])
            ->add('recherche', TextType::class, [
                'label' => 'Le nom de la sortie contient :',
                'required' => false,
                'attr' => ['placeholder' => 'Search'
                ]])
            ->add('dateDebutSearch', DateType::class,[
                'label' => 'Entre ',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('dateFinSearch', DateType::class,[
                'label' => 'et',
                'required' => false,
                'widget' => 'single_text'
            ])
            ->add('sortieOrganisee', CheckboxType::class,[
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false
            ])
            ->add('sortieInscrit', CheckboxType::class,[
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false
            ])
            ->add('sortieNonInscrit', CheckboxType::class,[
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false
            ])
            ->add('sortiePassee', CheckboxType::class,[
                'label' => 'Sorties passée',
                'required' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class
        ]);
    }
}
