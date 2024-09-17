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
            ['nombre' => 'Salud', 'descripcion' => 'Normas referentes a políticas de salud pública y regulaciones médicas.'],
            ['nombre' => 'Medio Ambiente', 'descripcion' => 'Normas sobre protección ambiental, conservación y recursos naturales.'],
            ['nombre' => 'Economía', 'descripcion' => 'Normas que regulan aspectos económicos y financieros.'],
            ['nombre' => 'Trabajo', 'descripcion' => 'Normas relacionadas con derechos laborales, empleo y seguridad social.'],
            ['nombre' => 'Justicia', 'descripcion' => 'Normas sobre el sistema judicial, procedimientos legales y derechos humanos.'],
            ['nombre' => 'Seguridad', 'descripcion' => 'Normas sobre seguridad pública, fuerzas armadas y defensa civil.'],
            ['nombre' => 'Transporte', 'descripcion' => 'Normas que regulan el transporte público y privado.'],
            ['nombre' => 'Tecnología', 'descripcion' => 'Normas relacionadas con el avance y regulación de tecnologías.'],
            ['nombre' => 'Cultura', 'descripcion' => 'Normas que fomentan y regulan actividades culturales y patrimoniales.'],
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
        return ['tema'];
    }
}
