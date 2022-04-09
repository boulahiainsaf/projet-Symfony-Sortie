<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnulerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


//            ->add('infosSortie', TextareaType::class, [
//                'label'=>'Motif',
////                'rows' => 15
////                array('attr' => array('cols' => '50', 'rows' => '50'))
//            ])

            ->add('infosSortie', TextareaType::class, [
                'label' => 'Motif',
                'attr' => array(
                    'placeholder' => 'Écrivez ici le motif d\'annulation...',
                    'value' => 'aaa',
                    'rows' => 7,
                    'cols' => 15,
                    'autocomplete'=> 'off'
                ),

//                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez rentrer un motif d\'annulation',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'le motif doit avoir au moins 10 characters',
                        'max' => 600,
                        'maxMessage' => 'le motif doit avoir 2000 characters au maximum',
                    ]),
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
