<?php

namespace App\Form;

use App\Entity\Idioma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NuevoIdiomaFormType extends AbstractType {
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
        ->add( 'denominacion', TextType::class,
        [
            'label' => 'Denominación: ',
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new NotBlank( [
                    'message' => 'Introduzca un nombre',
                ] ),
            ],
        ] )
        ;
    }

    public function configureOptions( OptionsResolver $resolver ) {
        $resolver->setDefaults( [
            'data_class' => Idioma::class,
        ] );
    }

    /**
    * {@inheritdoc}
        */

        public function getName() {
            return 'idioma_form';
        }
    }