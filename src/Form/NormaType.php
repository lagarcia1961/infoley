<?php

namespace App\Form;

use App\Entity\Norma;
use App\Entity\TipoNorma;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\File;

class NormaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('anio')
            ->add('titulo', TextType::class, [
                'label' => 'Título',
                'attr' => ['maxlength' => 255],
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'El nombre no puede tener más de {{ limit }} caracteres.',
                    ]),
                ]
            ])

            ->add('fechaPublicacion', null, [
                'widget' => 'single_text',
            ])
            ->add('textoCompleto')
            // Agregar un campo de texto para mostrar el nombre del archivo actual:
            ->add('currentFile', TextType::class, [
                'label' => 'Archivo actual',
                'mapped' => false, // Solo para mostrar, no lo mapeamos a la entidad
                'data' => $options['data']->getUrlPdf() ? $options['data']->getUrlPdf() : 'Sin adjuntar documento', // Mostrar el nombre del archivo actual o texto por defecto
                'required' => false,
                'attr' => [
                    'readonly' => true, // Hacer el campo solo de lectura
                ],
            ])
            ->add('urlPdf', FileType::class, [
                'label' => 'Subir una norma en formato PDF o Word',
                'mapped' => false, // No se asigna directamente a la entidad
                'required' => false, // Si no es obligatorio
                'constraints' => [
                    new File([
                        'maxSize' => '3072k', // Tamaño máximo
                        'mimeTypes' => [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ],
                        'mimeTypesMessage' => 'Por favor sube un archivo válido (PDF o Word)',
                    ])
                ],
            ])


            ->add('tipoNorma', EntityType::class, [
                'class' => TipoNorma::class,
                'choice_label' => 'nombre',
            ])
            ->add('guardar', SubmitType::class, [
                'label' => 'Guardar',
            ]);;;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}
