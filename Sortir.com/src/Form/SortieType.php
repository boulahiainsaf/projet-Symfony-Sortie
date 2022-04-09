<?php

namespace App\Form;


use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\DBAL\Cache\ArrayResult;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Array_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{

    private $em;


    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em )
    {
        $this->em = $em;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom de la sortie :'])
            ->add('dateHeureDebut'
                , DateTimeType::class, [
                    'html5' => true,
                    'widget' => 'single_text', 'label' => 'Date et heure de la sortie :'])
            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text', 'label' => 'Date limite d\'inscription :'])
            ->add('nbInscriptionsMax', IntegerType::class, ['label' => 'Nombre de place :'])
            ->add('duree', IntegerType::class, ['label' => 'Durée:'])
            ->add('infosSortie', TextareaType::class, ['label' => 'Description et infos :'])
            ->add('lieuForm', LieuType::class,[
                'label'=>'Ajouter un lieu :',
                'mapped'=>false,
                ])

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data=$event->getData();
                dump($data);
                $form = $event->getForm();
                $lieu = $data->getLieu();
                $ville = $lieu?$lieu->getVille():null;

                    $this->addElements($form, $ville);

            })

           ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
               $form = $event->getForm();
               $data = $event->getData();

               // Search for selected City and convert it into an Entity
               $ville = $this->em->getRepository(Ville::class)->find($data['ville'])?$this->em->getRepository(Ville::class)->find($data['ville']) : null;

               $this->addElements($form, $ville);

            });

    }
    protected function addElements(FormInterface $form, Ville $ville = null) {
        // 4. Add the province element
        // Neighborhoods empty, unless there is a selected City (Edit View)
       $form->add('ville', EntityType::class, [
            'class' => Ville::class,
            'choice_label' => 'nom',
            'placeholder' => 'Select la ville en premier ...',
            'mapped' => false,
           'data'=>$ville,
           'required' => false,
        ]);

          $lieux = $ville?$ville->getLieux():[];

          if(count($lieux)!==0){

              $form->add('lieu', EntityType::class, [
                  'class' => Lieu::class,
                  'choice_label' => 'nom',
                  'choices' => $lieux,
                  'required' => true,
                  'placeholder' => 'Select la ville en premier ...',

              ]);
          }else {
              $form->add('lieu', EntityType::class,[
                  'class' => Lieu::class,
                  'choice_label'=>'nom',
                  'placeholder' => 'Select la ville en premier ...',
                  'mapped'=>false,
                  'required' => false,

              ]);

          }


        // Add the Neighborhoods field with the properly data


    }


        public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,

        ]);
    }

}
