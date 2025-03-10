<?php

namespace App\DataFixtures;

use App\Entity\TipoNorma;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TipoNormaFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Datos de ejemplo para la tabla tipo_norma
        $tiposNorma = [
            ['Ley', 'Norma general y abstracta que regula una materia y que es dictada por el poder legislativo.', 1],
            ['Decreto', 'Norma dictada por el poder ejecutivo con fuerza de ley para regular situaciones específicas.', 2],
            ['Ordenanza', 'Norma dictada por un organismo local (municipalidad) para regular aspectos locales.', 3],
            ['Resolución', 'Decisión adoptada por una autoridad administrativa para resolver un asunto específico.', 4],
            ['Reglamento', 'Norma dictada para la aplicación y desarrollo de una ley, estableciendo procedimientos y detalles operativos.', 5],
            ['Disposición', 'Norma administrativa dictada por una autoridad competente para regular aspectos técnicos o administrativos.', 6],
            ['Acuerdo', 'Norma que resulta de un consenso entre distintas partes para regular un aspecto específico.', 7],
            ['Convenio', 'Acuerdo formal entre dos o más partes que tiene fuerza normativa en el contexto de una relación específica.', 8],
            ['Circular', 'Documento emitido por una entidad para informar o instruir sobre procedimientos y normativas internas.', 9]
        ];

        foreach ($tiposNorma as $data) {
            $tipoNorma = new TipoNorma();
            $tipoNorma->setNombre($data[0]);
            $tipoNorma->setRango($data[2]);
            $manager->persist($tipoNorma);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init', 'tipoNorma'];
    }
}
