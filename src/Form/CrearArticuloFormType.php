<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CrearArticuloFormType extends AbstractType
{
    /**
    * {@inheritdoc}
        */

        public function buildForm( FormBuilderInterface $builder, array $options )
        {
            $builder
            ->add(
                'titulo',
                TextType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'sipnosis',
                TextareaType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'redaccion',
                TextareaType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'fechaPublicacion',
                DateType::class,
                [
                    'constraints' => [new NotBlank()],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'idioma',
                EntityType::class,
                [
                    'class' => Idioma::class,
                    'choice_label' => 'denominacion',
                ]
            )
            ->add(
                'categoria',
                EntityType::class,
                [
                    'class' => Categoria::class,
                    'choice_label' => 'denominacion',
                ]
            )
            ->add(
                'create',
                SubmitType::class,
                [
                    'attr' => ['class' => 'form-control btn-primary pull-right'],
                    'label' => 'Crear'
                ]
            );
        }

        /**
        * {@inheritdoc}
            */

            public function configureOptions( OptionsResolver $resolver )
            {
                $resolver->setDefaults( [
                    'data_class' => Articulo::class,
                    ]);
            }

}