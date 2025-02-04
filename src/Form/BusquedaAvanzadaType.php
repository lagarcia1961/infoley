<?php

namespace App\Form;

use App\Entity\Dependencia;
use App\Entity\Tema;
use App\Entity\TipoNorma;
use App\Repository\DependenciaRepository;
use App\Repository\TemaRepository;
use App\Repository\TipoNormaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class BusquedaAvanzadaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $hoy = (new \DateTime())->format('Y-m-d'); // Formatear fecha actual en 'Y-m-d'

        $builder->add('tipoNorma', EntityType::class, [
            'class' => TipoNorma::class,
            'label' => 'Tipo de norma',
            'required' => false,
            'choice_label' => 'nombre',
            'query_builder' => function (TipoNormaRepository $tn) {
                return $tn->createQueryBuilder('tn')
                    ->where('tn.isActive = :isActive')
                    ->setParameter('isActive', true)
                    ->orderBy('tn.nombre', 'ASC');
            },
            'empty_data' => null,
            'placeholder' => 'Todas'
        ])
            ->add('numero', IntegerType::class, [
                'label' => 'Número',
                'required' => false
            ])
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
                'required' => false
            ])
            ->add('texto', TextType::class, [
                'label' => 'Texto',
                'required' => false,
            ])

            ->add('dependencia', EntityType::class, [
                'class' => Dependencia::class,
                'required' => false,
                'choice_label' => 'nombre',
                'query_builder' => function (DependenciaRepository $d) {
                    return $d->createQueryBuilder('d')
                        ->where('d.isActive = :isActive')
                        ->setParameter('isActive', true)
                        ->orderBy('d.nombre', 'ASC');
                },
                'empty_data' => null,
                'placeholder' => 'Seleccione una dependencia'
            ])
            ->add('fechaDesde', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha publicación desde',
                'required' => false,
                'attr' => [
                    'max' => $hoy
                ],
            ])
            ->add('fechaHasta', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha publicación hasta',
                'required' => false,
                'attr' => [
                    'max' => $hoy
                ],
            ])
            ->add('tema', EntityType::class, [
                'class' => Tema::class,
                'required' => false,
                'choice_label' => 'nombre',
                'query_builder' => function (TemaRepository $t) {
                    return $t->createQueryBuilder('t')
                        ->where('t.isActive = :isActive')
                        ->setParameter('isActive', true)
                        ->orderBy('t.nombre', 'ASC');
                },
                'empty_data' => null,
                'placeholder' => 'Seleccione un tema'
            ])
            ->add('buscar', SubmitType::class, [
                'label' => 'Buscar normativa',
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();


            $hoy = new \DateTime();

            $fechaDesde = $data['fechaDesde'];
            $fechaHasta = $data['fechaHasta'];

            if ($fechaHasta && $fechaHasta > $hoy) {
                $form->get('fechaHasta')->addError(new FormError('La fecha "hasta" no puede ser mayor a hoy.'));
            }

            if ($fechaDesde && $fechaDesde > $hoy) {
                $form->get('fechaDesde')->addError(new FormError('La fecha "desde" no puede ser mayor a hoy.'));
            }

            if ($fechaDesde && $fechaHasta && $fechaDesde > $fechaHasta) {
                $form->get('fechaDesde')->addError(new FormError('La fecha "desde" no puede ser mayor que la fecha "hasta".'));
                $form->get('fechaHasta')->addError(new FormError('La fecha "hasta" no puede ser menor que la fecha "desde".'));
            }

            if (empty($data['numero']) && empty($data['anio']) && empty($data['texto']) && empty($data['dependencia']) && empty($data['tema']) && empty($data['fechaDesde']) && empty($data['fechaHasta'])) {
                $message = 'Debes completar al menos 2 criterios de búsqueda.';
                $form->get('numero')->addError(new FormError($message));
                $form->get('anio')->addError(new FormError($message));
                $form->get('texto')->addError(new FormError($message));
                $form->get('dependencia')->addError(new FormError($message));
                $form->get('tema')->addError(new FormError($message));
                $form->get('fechaDesde')->addError(new FormError($message));
                $form->get('fechaHasta')->addError(new FormError($message));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
