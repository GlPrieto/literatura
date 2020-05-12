<?php

namespace App\Form;

use App\Entity\Categoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class NuevaCategoriaFormType extends AbstractType {
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
        ->add( 'descripcion', TextType::class,
        [
            'label' => 'Descripción: ',
            'attr' => ['class' => 'form-control'],
            'required' => false

        ] )
        ;
    }

    public function configureOptions( OptionsResolver $resolver ) {
        $resolver->setDefaults( [
            'data_class' => Categoria::class,
        ] );
    }

    /**
    * {@inheritdoc}
        */

        public function getName() {
            return 'categoria_form';
        }
    }
