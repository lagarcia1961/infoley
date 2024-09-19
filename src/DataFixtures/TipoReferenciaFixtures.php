<?php

namespace App\DataFixtures;

use App\Entity\TipoReferencia;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TipoReferenciaFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        // Datos de ejemplo para la tabla tipo_norma
        $tiposReferencias = [
            ['Módifica'],
            ['Deroga'],
            ['Complementa'],
            ['Anula'],
            ['Hace referencia a'],
            ['Actualiza'],
            ['Aclara']
        ];

        foreach ($tiposReferencias as $data) {
            $tipoReferencia = new TipoReferencia();
            $tipoReferencia->setNombre($data[0]);
            $manager->persist($tipoReferencia);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init', 'tipoReferencia'];
    }
}
