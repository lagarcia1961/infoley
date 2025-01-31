<?php

namespace App\Form;

use App\Constants\Rol;
use App\Entity\Dependencia;
use App\Entity\Norma;
use App\Entity\Tema;
use App\Entity\TipoNorma;
use App\Entity\TipoReferencia;
use App\Repository\DependenciaRepository;
use App\Repository\TemaRepository;
use App\Repository\TipoNormaRepository;
use App\Repository\TipoReferenciaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('numero', IntegerType::class, [
                'label' => 'Número <span style="color:red">*</span>',
                'label_html' => true,
            ])
            ->add('fechaSancion', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha de sanción <span style="color:red">*</span>',
                'label_html' => true,
            ])
            ->add('titulo', TextType::class, [
                'label' => 'Título <span style="color:red">*</span>',
                'label_html' => true,
                'required' => true,
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
                'required' => false,
                'label' => 'Fecha de publicación',
                'label_html' => true,
            ])
            ->add('textoCompleto', HiddenType::class)
            ->add('textoCompletoHtml', TextareaType::class, [
                'label' => 'Texto de la norma <span style="color:red">*</span>',
                'label_html' => true,
                'attr' => [
                    'class' => 'trumbowyg-editor',
                ],
            ]);
        if ($options['is_edit'] && $options['modificado']) {
            $builder->add('textoCompletoModificadoHtml', TextareaType::class, [
                'label' => 'Texto modificado',
                'label_html' => true,
                'attr' => [
                    'class' => 'trumbowyg-editor',
                ],
            ]);
        }
        $builder
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
                'attr' => [
                    'accept' => '.pdf'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '100M', // Tamaño máximo
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Por favor sube un archivo PDF válido.',
                    ])
                ],
            ]);
        if ($user->getRol()->getId() == Rol::ROLE_CARGA) {
            $builder->add('tipoNorma', EntityType::class, [
                'class' => TipoNorma::class,
                'required' => true,
                'choice_label' => 'nombre',
                'query_builder' => function (TipoNormaRepository $tn) use ($user) {
                    return $tn->createQueryBuilder('tn')
                        ->join('tn.usuarioTipoNormas', 'utn')
                        ->where('utn.user = :user')
                        ->setParameter('user', $user)
                        ->where('tn.isActive = :isActive')
                        ->setParameter('isActive', true)
                        ->orderBy('tn.nombre', 'ASC');
                },
                'empty_data' => null,
                'placeholder' => 'Seleccione un Tipo de Norma',
                'label' => 'Tipo de norma <span style="color:red">*</span>',
                'label_html' => true,
            ]);
        } else {
            $builder->add('tipoNorma', EntityType::class, [
                'class' => TipoNorma::class,
                'required' => true,
                'choice_label' => 'nombre',
                'query_builder' => function (TipoNormaRepository $tn) {
                    return $tn->createQueryBuilder('tn')
                        ->where('tn.isActive = :isActive')
                        ->setParameter('isActive', true)
                        ->orderBy('tn.nombre', 'ASC');
                },
                'empty_data' => null,
                'placeholder' => 'Seleccione un Tipo de Norma',
                'label' => 'Tipo de norma <span style="color:red">*</span>',
                'label_html' => true,
            ]);
        }
        $builder->add('tipoReferenciaOrigen', EntityType::class, [
            'class' => TipoReferencia::class,
            'label' => 'Tipo de referencia',
            'required' => false,
            'disabled' => true,
            'choice_label' => 'nombre',
            'query_builder' => function (TipoReferenciaRepository $tr) {
                return $tr->createQueryBuilder('tr')
                    ->where('tr.isActive = :isActive')
                    ->setParameter('isActive', true)
                    ->orderBy('tr.nombre', 'ASC');
            },
            'empty_data' => null,
            'mapped' => false,
            'placeholder' => 'Seleccione un Tipo de referencia'
        ])
            ->add('normaOrigen', ChoiceType::class, [
                'placeholder' => 'Seleccione una norma',
                'label' => 'Norma',
                'required' => false,
                'mapped' => false,
                'disabled' => true,
                'attr'=>[
                    'class'=>'form-select'
                ]
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
            ->add('dependencia', EntityType::class, [
                'class' => Dependencia::class,
                'required' => true,
                'choice_label' => 'nombre',
                'query_builder' => function (DependenciaRepository $tn) {
                    return $tn->createQueryBuilder('tn')
                        ->where('tn.isActive = :isActive')
                        ->setParameter('isActive', true)
                        ->orderBy('tn.nombre', 'ASC');
                },
                'empty_data' => null,
                'placeholder' => 'Seleccione una dependencia',
                'label' => 'Dependencia <span style="color:red">*</span>',
                'label_html' => true,
            ])
            ->add('guardar', SubmitType::class, [
                'label' => 'Guardar',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
            'is_edit' => false,
            'modificado' => false,
        ]);
    }
}
