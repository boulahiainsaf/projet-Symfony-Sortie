<?php

namespace App\Form;

use App\Entity\Campus;
use App\Services\FilterCampus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterCampusType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('nomCampusContient', TextType::class, [
                'attr' => ['class' => 'search-bar','placeholder'=>'Le nom du campus contient...'],
                'label'=>' ',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilterCampus::class
        ]);
    }
}