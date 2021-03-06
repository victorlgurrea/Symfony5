<?php

namespace App\DataFixtures;

use App\Entity\Marcador;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MarcadoresFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $marcador = new Marcador();
        $marcador->setNombre('Tesla');
        $marcador->setCategoria($this->getReference(CategoriasFixtures::CATEGORIA_TECNOLOGICAS_REFERENCIA));
        $marcador->setUsuario($this->getReference(UsuariosFixtures::USUARIO_ADMIN_REFERENCIA));
        $marcador->setUrl('https://www.tesla.com/');
        $manager->persist($marcador);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsuariosFixtures::class,
            CategoriasFixtures::class
        ];
    }
}
