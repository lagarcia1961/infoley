<?php

namespace App\DataFixtures;

use App\Entity\Tema;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TemaFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $temas = [
            ['nombre' => 'Educación', 'descripcion' => 'Normas relacionadas con el sistema educativo y sus reformas.'],
        ];

        foreach ($temas as $temaData) {
            $tema = new Tema();
            $tema->setNombre($temaData['nombre']);
            $tema->setDescripcion($temaData['descripcion']);

            $manager->persist($tema);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init', 'tema'];
    }
}
