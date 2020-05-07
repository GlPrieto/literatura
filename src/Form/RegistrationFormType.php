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

class RegistrationFormType extends AbstractType {
    public function buildForm( FormBuilderInterface $builder, array $options ) {
        $builder
        ->add( 'email',
        TextType::class,
        [
            'label' => 'Email: ',

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
        ->add( 'agreeTerms', 
        CheckboxType::class, [
            'label' => 'Acepto las normas del sitio',
            'mapped' => false,
            'constraints' => [
                new IsTrue( [
                    'message' => 'Debes aceptar nuestras normas.',
                ] ),
            ],
        ] )
        ->add( 'plainPassword', PasswordType::class, [
            'label' => 'Contraseña: ',
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'constraints' => [
                new NotBlank( [
                    'message' => 'Introduzca una contraseña',
                ] ),
                new Length( [
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ] ),
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