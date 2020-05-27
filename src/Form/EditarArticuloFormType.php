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
            'attr' => ['title' => 'Título',],
            'constraints' => [
                new NotBlank( [
                    'message' => 'Introduzca un titulo',
                ] ),
                new Length( [
                    'min' => 6,
                    'minMessage' => 'Su artículo debe tener un mínimo de {{ limit }} letras',
                ] ),
            ],
        ] )
        ->add( 'sipnosis', TextareaType::class,
        [
            'label' => 'Descripción: ',
            'attr' => [
                'title' => 'Breve descripción del artículo',
            ],
            'constraints' => [
                new NotBlank( [
                    'message' => 'Este campo debe estar relleno para publicar',
                ] ),
                new Length( [
                    'min' => 20,
                    'minMessage' => 'Su artículo debe tener un mínimo de {{ limit }} letras',
                ] ),
            ],
            'required' => false

        ] )
        ->add( 'redaccion', TextareaType::class,
        [
            'label' => 'Redacción: ',
            'attr' => [
                'rows' => '20',
                'cols' => '10',
                'title' => 'Redacción del artículo',
            ],            'constraints' => [
                new NotBlank( [
                    'message' => 'Este campo debe estar relleno para publicar',
                ] ),
                new Length( [
                    'min' => 100,
                    'minMessage' => 'Su artículo debe tener un mínimo de {{ limit }} letras',
                ] ),
            ],
        ] )
        // ...
        ->add( 'image', FileType::class, [
            'data_class'=> null,
            'label' => 'Imagen: ',
            'attr' => ['title' => 'Imagen'],
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
                        'image/svg',
                    ],
                    'mimeTypesMessage' => 'Por favor, carga un formato de imagen valido',
                ] )
            ],
        ] )
        // ...
        ->add( 'idioma', EntityType::class, [
            'class' => Idioma::class,
            'label' => 'Idioma: ',
            'attr' => ['title' => 'Idioma'],
            'choice_label' => 'denominacion',
        ] )
        ->add( 'categoria', EntityType::class, [
            'class' => Categoria::class,
            'label' => 'Categoría: ',
            'attr' => ['title' => 'Categoría'],
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
