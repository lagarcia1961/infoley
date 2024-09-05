<?php

namespace App\DataFixtures;
use App\Entity\TipoNorma;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TipoNormaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       // Datos de ejemplo para la tabla tipo_norma
       $tiposNorma = [
        ['Ley', 'Norma general y abstracta que regula una materia y que es dictada por el poder legislativo.'],
        ['Decreto', 'Norma dictada por el poder ejecutivo con fuerza de ley para regular situaciones específicas.'],
        ['Disposición', 'Norma administrativa dictada por una autoridad competente para regular aspectos técnicos o administrativos.'],
        ['Resolución', 'Decisión adoptada por una autoridad administrativa para resolver un asunto específico.'],
        ['Ordenanza', 'Norma dictada por un organismo local (municipalidad) para regular aspectos locales.'],
        ['Reglamento', 'Norma dictada para la aplicación y desarrollo de una ley, estableciendo procedimientos y detalles operativos.'],
        ['Circular', 'Documento emitido por una entidad para informar o instruir sobre procedimientos y normativas internas.'],
        ['Acuerdo', 'Norma que resulta de un consenso entre distintas partes para regular un aspecto específico.'],
        ['Convenio', 'Acuerdo formal entre dos o más partes que tiene fuerza normativa en el contexto de una relación específica.'],
    ];

    foreach ($tiposNorma as $data) {
        $tipoNorma = new TipoNorma();
        $tipoNorma->setNombre($data[0]);
        $tipoNorma->setDescripcion($data[1]);
        $manager->persist($tipoNorma);
    }

        $manager->flush();
    }
}
