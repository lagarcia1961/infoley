<?php

namespace App\Form;

use App\Entity\TipoNorma;
use App\Repository\TipoNormaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class BusquedaSimpleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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

            ->add('buscar', SubmitType::class, [
                'label' => 'Buscar normativa',
            ]);

        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     $form = $event->getForm();
        //     $data = $event->getData();

        //     // Verificamos si ambos campos 'numero' y 'anio' están vacíos
        //     if (empty($data['numero']) && empty($data['anio'])) {
        //         $message = 'Debes completar el campo Número o el campo Año.';
        //         $form->get('numero')->addError(new FormError($message));
        //         $form->get('anio')->addError(new FormError($message));
        //     }
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
