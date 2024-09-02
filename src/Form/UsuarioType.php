<?php

namespace App\Form;

use App\Entity\Rol;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $is_edit = @$options['is_edit'];

        if ($is_edit) {
            $builder->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'required' => false,
                'attr' => ['autocomplete' => 'new-password']
            ]);
        } else {
            $builder->add('password', PasswordType::class, [
                'label' => 'Contraseña <span style="color:red">*</span>',
                'label_html' => true,
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'El campo Contraseña es obligatorio.']),
                ],
            ]);
        }
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail <span style="color:red">*</span>',
                'label_html' => true,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ingrece correo electrónico',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'El E-mail es obligatorio.']),
                    new Email(['message' => 'El E-mail no es válido.']),
                ],
            ])
            ->add('rol', EntityType::class, [
                'class' => Rol::class,
                'placeholder' => 'Seleccione una rol',
                'choice_label' => 'descripcion',
                'label' => 'Rol <span style="color:red">*</span>',
                'label_html' => true,
                'constraints' => [
                    new NotBlank(['message' => 'El campo Rol es obligatorio.']),
                ],
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nombre completo <span style="color:red">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'El campo Nombre es obligatorio.']),
                ],
            ])
            ->add('usuario', TextType::class, [
                'label' => 'Usuario <span style="color:red">*</span>',
                'label_html' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'El campo Usuario es obligatorio.']),
                ],
            ])
            ->add('guardar', SubmitType::class, [
                'label' => 'Guardar',
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false
        ]);
    }
}
