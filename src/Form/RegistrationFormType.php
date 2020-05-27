<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
        EmailType::class,
        [
            'label' => '*Email: ',
            'attr' => ['class' => 'form-control'],
            'help' => 'Asegurese de introducir un email válido'

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
            'label' => '*Usuario: ',
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
                    'maxSize' => '1024k',
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
        ->add( 'agreeTerms', 
        CheckboxType::class, [
            'label' => '*Acepto las normas del sitio',
            'mapped' => false,
            'constraints' => [
                new IsTrue( [
                    'message' => 'Debes aceptar nuestras normas.',
                ] ),
            ],
        ] )
        ->add( 'plainPassword', RepeatedType::class, [
            'first_options'  => ['label' => '*Contraseña: '],
            'second_options' => ['label' => '*Repite la contraseña: '],
            'attr' => ['class' => 'form-control'],
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'invalid_message' => 'Ambas contraseñas deben coincidir.',
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
