<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class EditarUsuarioFormType extends AbstractType {
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
        ->add( 'email',
        TextType::class,
        [
            'label' => 'Email: ',
            'attr' => ['class' => 'form-control']

        ] )
        ->add( 'nombre',
        TextType::class,
        [
            'label' => 'Nombre: ',
            'attr' => ['class' => 'form-control'],
            'required' => false
        ] )
        ->add( 'apellidos',
        TextType::class,
        [
            'label' => 'Apellidos: ',
            'attr' => ['class' => 'form-control'],
            'required' => false
        ] )
        ->add( 'firmaUsuario',
        TextType::class,
        [
            'label' => 'Usuario: ',
            'attr' => ['class' => 'form-control']
        ] )
        // ...
        ->add( 'image', FileType::class, [
            'data_class'=> null,
            'label' => 'Imagen: ',
            'attr' => ['class' => 'form-control'],
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
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver ) {
        $resolver->setDefaults( [
            'data_class' => Usuario::class,
        ] );
    }

    /**
    * {@inheritdoc}
        */

        public function getName() {
            return 'autor_form';
        }
    }
