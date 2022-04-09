<?php

namespace App\Form;

use App\Services\FilterParticipants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomParticipant', TextType::class, [
                'attr' => ['class' => 'search-bar','placeholder'=>'search'],
                'label'=>'Chercher par nom, prènom, email ou pseudo :',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterParticipants::class,
        ]);
    }
}
