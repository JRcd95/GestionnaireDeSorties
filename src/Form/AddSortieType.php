<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSortieType extends AbstractType
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
            ->add('ville', EntityType::class, [
                'mapped'=> false,
                'class'=> Ville::class,
                'choice_label'=> 'nomVille',
                'placeholder' => "--Ville--",
                'label'=> false
            ])
            ->add('lieu', ChoiceType::class, [
                'label' => false,
                'placeholder' => "--Sélectionner en premier une ville--"

            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn']
            ])
            ->add('publier', SubmitType::class, [
                'label' => 'Publier  la sortie',
                'attr' => ['class' => 'btn']
            ])
        ;

            $formModifier = function (FormInterface $form, Ville $ville=null){
                $lieux = ($ville === null) ? [] : $ville->getLieux();
                $form->add('lieu', EntityType::class, [
                    'class'=> Lieu::class,
                    'choices' => $lieux,
                    'choice_label'=> 'nomLieu',
                    'label' => false,
                    'placeholder' => "--Lieu--",
                    'required' => false
                ]);
            };

        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier){
                $ville = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $ville);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
