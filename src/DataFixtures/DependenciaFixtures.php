<?php

namespace App\DataFixtures;

use App\Entity\Dependencia;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class DependenciaFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $dependencias = [
            ['Ministerio de educación de Tierra del Fuego'],
        ];

        foreach ($dependencias as $data) {
            $dependencia = new Dependencia();
            $dependencia->setNombre($data[0]);
            $manager->persist($dependencia);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init', 'dependencias'];
    }
}
