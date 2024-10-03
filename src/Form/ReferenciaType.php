<?php

namespace App\Form;

use App\Entity\Norma;
use App\Entity\Referencia;
use App\Entity\TipoReferencia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipoReferencia', EntityType::class, [
                'class' => TipoReferencia::class,
                'choice_label' => 'id',
            ])
            ->add('normaOrigen', EntityType::class, [
                'class' => Norma::class,
                'choice_label' => 'id',
            ])
            ->add('normaDestino', EntityType::class, [
                'class' => Norma::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Referencia::class,
        ]);
    }
}
