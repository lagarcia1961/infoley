<?php

namespace App\Form;

use App\Entity\Seccion;
use App\Entity\Tema;
use App\Repository\TemaRepository;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orden', IntegerType::class, [
                'required' => true,
                'label' => 'Orden <span style="color:red">*</span>',
                'label_html' => true,
                'attr' => [
                    'min' => 1,
                ],
            ]);
        if ($options['is_edit']) {
            $builder->add('tema', EntityType::class, [
                'class' => Tema::class,
                'disabled' => true,
                'choice_label' => 'nombre',
                'query_builder' => function (TemaRepository $t) {
                    return $t->createQueryBuilder('t')
                        ->where('t.isActive = :isActive')
                        ->setParameter('isActive', true)
                        ->orderBy('t.nombre', 'ASC');
                },
                'empty_data' => null,
                'placeholder' => 'Seleccione un Tema',
                'label' => 'Tema <span style="color:red">*</span>',
                'label_html' => true,
            ]);
        } else {
            $builder->add('tema', EntityType::class, [
                'class' => Tema::class,
                'required' => true,
                'choice_label' => 'nombre',
                'query_builder' => function (TemaRepository $t) {
                    return $t->createQueryBuilder('t')
                        ->where('t.isActive = :isActive')
                        ->setParameter('isActive', true)
                        ->orderBy('t.nombre', 'ASC');
                },
                'empty_data' => null,
                'placeholder' => 'Seleccione un Tema',
                'label' => 'Tema <span style="color:red">*</span>',
                'label_html' => true,
            ]);
        }

        $builder->add('guardar', SubmitType::class, [
            'label' => 'Guardar',
        ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seccion::class,
            'is_edit' => false
        ]);
    }
}
