<?php

namespace App\Form;

use App\Entity\Norma;
use App\Entity\Seccion;
use App\Entity\SeccionNorma;
use App\Repository\NormaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeccionNormaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $tema = $options['tema'];

        $builder
            ->add('titulo', TextType::class, [
                'required' => false,
                'label' => 'Título',
                'label_html' => true,
            ]);
        if ($options['is_edit']) {
            $builder
                ->add('norma', EntityType::class, [
                    'class' => Norma::class,
                    'required' => false,
                    'disabled'=>true,
                    'label' => 'Norma <span style="color:red">*</span>',
                    'label_html' => true,
                    'choice_label' => function (Norma $norma) {
                        // Construir la etiqueta con número, año de sanción y título
                        $anioSancion = $norma->getFechaSancion() ? $norma->getFechaSancion()->format('Y') : '';
                        $titulo = $norma->getTitulo() ?? 'Sin Título';
                        return sprintf('%d - %s - %s', $norma->getNumero(), $anioSancion, $titulo);
                    },
                    'query_builder' => function (NormaRepository $nr) use ($tema) {
                        return $nr->createQueryBuilder('n')
                            ->join('n.normaTemas', 'nt') // Relación Norma -> NormaTema
                            ->where('nt.tema = :tema') // Filtrar por el tema dado
                            ->setParameter('tema', $tema)
                            ->orderBy('n.numero', 'ASC'); // Ordenar por número
                    },
                    'required' => true,
                    'placeholder' => 'Seleccione una Norma',
                ]);
        } else {

            $builder
                ->add('norma', EntityType::class, [
                    'class' => Norma::class,
                    'required' => true,
                    'label' => 'Norma <span style="color:red">*</span>',
                    'label_html' => true,
                    'choice_label' => function (Norma $norma) {
                        // Construir la etiqueta con número, año de sanción y título
                        $anioSancion = $norma->getFechaSancion() ? $norma->getFechaSancion()->format('Y') : '';
                        $titulo = $norma->getTitulo() ?? 'Sin Título';
                        return sprintf('%d - %s - %s', $norma->getNumero(), $anioSancion, $titulo);
                    },
                    'query_builder' => function (NormaRepository $nr) use ($tema) {
                        return $nr->createQueryBuilder('n')
                            ->join('n.normaTemas', 'nt') // Relación Norma -> NormaTema
                            ->where('nt.tema = :tema') // Filtrar por el tema dado
                            ->setParameter('tema', $tema)
                            ->orderBy('n.numero', 'ASC'); // Ordenar por número
                    },
                    'required' => true,
                    'placeholder' => 'Seleccione una Norma',
                ]);
        }
        $builder
            ->add('orden', IntegerType::class, [
                'required' => true,
                'label' => 'Orden <span style="color:red">*</span>',
                'label_html' => true,
                'attr' => [
                    'min' => 1,
                ],
            ]);


        $builder->add('guardar', SubmitType::class, [
            'label' => 'Guardar',
        ]);;;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeccionNorma::class,
            'tema' => null,
            'is_edit' => false
        ]);
    }
}
