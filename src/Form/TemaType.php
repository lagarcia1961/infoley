<?php

namespace App\Form;

use App\Entity\Tema;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class TemaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'attr' => ['maxlength' => 100],
                'constraints'=>[
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'El nombre no puede tener más de {{ limit }} caracteres.',
                    ]),
                ]
            ])
            ->add('descripcion', TextType::class, [
                'label' => 'Descripción',
                'attr' => ['maxlength' => 255],
                'constraints'=>[
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'El nombre no puede tener más de {{ limit }} caracteres.',
                    ]),
                ]
            ])

            ->add('guardar', SubmitType::class, [
                'label' => 'Guardar',
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tema::class,
        ]);
    }
}
