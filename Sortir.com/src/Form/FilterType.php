<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Services\FilterSorties;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'class'=>Campus::class,
                'choice_label'=>'nom',
                'data'=>$options['user']->getCampus(),
                'label'=>'Campus :',
                'placeholder'=>'TOUS LES CAMPUS',
                'required' => false
            ])
            ->add('nomSortie', TextType::class, [
                'attr' => ['class' => 'search-bar','placeholder'=>'search'],
                'label'=>'Le nom de la sortie contient :',
                'required' => false,
            ])
            ->add('dateDebut', DateType::class, ['html5' => true, 'widget' => 'single_text', 'label'=>'Entre', 'required' => false])
            ->add('dateFin', DateType::class, ['html5' => true, 'widget' => 'single_text', 'label'=>'et', 'required' => false])
            ->add('sortiesOrganises', CheckboxType::class, [
                'label'    => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
                'data'=> false
            ])
            ->add('sortiesInscrites', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
                'data'=> false
            ])
            ->add('sortiesPasInscrites', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
                'data'=> false
            ])
            ->add('sortiesPassees', CheckboxType::class, [
                'label'    => 'Sorties passées',
                'required' => false,
                'data'=> false

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterSorties::class,
            'user'=>Participant::class
        ]);
    }

}
