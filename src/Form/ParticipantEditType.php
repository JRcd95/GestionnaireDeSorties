<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => false
            ])
            ->add('nom', TextType::class, [
                'label' => false
            ])
            ->add('prenom', TextType::class, [
                'label' => false
            ])
            ->add('telephone', TextType::class, [
                'label' => false
            ])
            ->add('pseudo', TextType::class, [
                'label' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'invalid_message'=>'Les deux doivent être identiques',
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'maxlength' => 50
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'maxlength' => 50
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
