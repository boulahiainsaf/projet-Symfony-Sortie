<?php

namespace App\Form;

use App\Services\FilterVilles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterVillesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomVillesContient', TextType::class, [
                'attr' => ['class' => 'search-bar','placeholder'=>'Le nom de la ville contient...'],
                'label'=>' ',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterVilles::class
        ]);
    }
}
