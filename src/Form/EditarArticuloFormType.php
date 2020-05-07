<?php

namespace App\Form;

use App\Entity\Articulo;
use App\Entity\Idioma;
use App\Entity\Categoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class EditarArticuloFormType extends AbstractType {
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
        ->add( 'titulo', TextType::class,
        [
            'label' => 'Título: ',
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new NotBlank( [
                    'message' => 'Introduzca un título',
                ] ),
                new Length( [
                    'min' => 2,
                ] ),
            ],
        ] )
        ->add( 'sipnosis', TextareaType::class,
        [
            'label' => 'Descripción: ',
            'attr' => ['class' => 'form-control'],
            'required' => false

        ] )
        ->add( 'redaccion', TextareaType::class,
        [
            'label' => 'Redacción: ',
            'constraints' => [
                new NotBlank( [
                    'message' => 'Introduzca texto',
                ] ),
                new Length( [
                    'min' => 100
                ] ),
            ],
        ] )
        // ...
        ->add( 'image', FileType::class, [
            'data_class'=> null,
            'label' => 'Imagen: ',
            // Este atributo no está asociado con ningún atributo
            'mapped' => false,
            // Opcional.
            'required' => false,
            // Al no estar mapeado:
            'constraints' => [
                new File( [
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Por favor, carga un formato de imagen valido',
                ] )
            ],
        ] )
        // ...
        ->add( 'idioma', EntityType::class, [
            'class' => Idioma::class,
            'label' => 'Idioma: ',
            'attr' => ['class' => 'form-control'],
            'choice_label' => 'denominacion',
        ] )
        ->add( 'categoria', EntityType::class, [
            'class' => Categoria::class,
            'label' => 'Categoría: ',
            'attr' => ['class' => 'form-control'],
            'choice_label' => 'denominacion',
        ] )
        ;
    }

    public function configureOptions( OptionsResolver $resolver ) {
        $resolver->setDefaults( [
            'data_class' => Articulo::class,
        ] );
    }

    /**
    * {@inheritdoc}
        */

        public function getName() {
            return 'articulo_form';
        }
    }
