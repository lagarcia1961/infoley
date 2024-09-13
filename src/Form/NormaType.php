<?php

namespace App\Form;

use App\Entity\Norma;
use App\Entity\Tema;
use App\Entity\TipoNorma;
use App\Repository\NormaRepository;
use App\Repository\TemaRepository;
use App\Repository\TipoNormaRepository;
use App\Repository\UsuarioTipoNormaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class NormaType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $this->security->getUser();


        $builder
            ->add('numero', IntegerType::class, ['label' => 'Número'])
            ->add('anio', IntegerType::class, [
                'constraints' => [
                    new Assert\Range([
                        'min' => 1900,
                        'max' => (int) date('Y'), // Año actual
                        'notInRangeMessage' => 'El año debe estar entre {{ min }} y {{ max }}.',
                    ]),
                ],
                'attr' => [
                    'min' => 1900,
                    'max' => (int) date('Y')
                ],
                'label' => 'Año',
            ])
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

            ->add('fechaPublicacion', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha de publicación'
            ])
            ->add('textoCompleto', TextareaType::class)
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
                'required' => true,
                'choice_label' => 'nombre',
                'query_builder' => function (TipoNormaRepository $tn) use ($user) {
                    return $tn->createQueryBuilder('tn')
                        ->join('tn.usuarioTipoNormas', 'utn')
                        ->where('utn.user = :user')
                        ->setParameter('user', $user)
                        ->orderBy('tn.nombre', 'ASC');
                },
                'empty_data' => null,
                'placeholder'=>'Seleccione un Tipo de Norma'
            ])
            ->add('temas', EntityType::class, [
                'label' => 'Temas',
                'class' => Tema::class,
                'choice_label' => 'nombre',
                'required' => false,
                'multiple' => true,
                'expanded' => false, // Cambiar a false para usar select en lugar de checkboxes
                'mapped' => false,
                'query_builder' => function (TemaRepository $t) {
                    return $t->createQueryBuilder('t')
                        ->where('t.isActive = :isActive')
                        ->setParameter('isActive', true)
                        ->orderBy('t.nombre', 'ASC');
                },
                'placeholder' => 'Seleccione uno o varios temas',
                'attr' => [
                    'class' => 'choice_multiple_default',
                    'placeholder' => 'Seleccione uno o varios temas',
                    'aria-label' => 'Seleccione uno o varios temas'
                ],
            ])

            // ->add('normaOrigen', EntityType::class, [
            //     'class' => Norma::class,
            //     'choice_label' => 'titulo',
            //     'query_builder' => function (NormaRepository $n) {
            //         return $n->createQueryBuilder('n')
            //             ->where('n.isActive = :isActive')
            //             ->setParameter('isActive', true)
            //             ->orderBy('n.titulo', 'ASC');
            //     },
            //     'empty_data' => null,
            //     'placeholder'=>'Seleccione una norma',
            //     'attr' => [
            //         'class' => 'choices-single-default-label',
            //         'placeholder' => 'Seleccione una norma de origen',
            //         'aria-label' => 'Seleccione una norma de origen'
            //     ]
            // ])
            // ->add('normaDestino', EntityType::class, [
            //     'class' => Norma::class,
            //     'choice_label' => 'titulo',
            //     'query_builder' => function (NormaRepository $n) {
            //         return $n->createQueryBuilder('n')
            //             ->where('n.isActive = :isActive')
            //             ->setParameter('isActive', true)
            //             ->orderBy('n.titulo', 'ASC');
            //     },
            //     'empty_data' => null,
            //     'placeholder'=>'Seleccione una norma',
            //     'attr' => [
            //         'class' => 'choices-single-default-label',
            //         'placeholder' => 'Seleccione una norma de destino',
            //         'aria-label' => 'Seleccione una norma de destino'
            //     ]
            // ])



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
