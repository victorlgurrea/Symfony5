<?php

namespace App\DataFixtures;

use App\Entity\Categoria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoriasFixtures extends Fixture implements DependentFixtureInterface
{
    public const CATEGORIA_TECNOLOGICAS_REFERENCIA = 'categoria.tecnologicas';

    public function load(ObjectManager $manager)
    {
        $categoria = new Categoria();
        $categoria->setNombre('tecnologicas');
        $categoria->setColor('orange');
        $categoria->setUsuario($this->getReference(UsuariosFixtures::USUARIO_ADMIN_REFERENCIA));
        $manager->persist($categoria);

        $manager->flush();

        $this->addReference(self::CATEGORIA_TECNOLOGICAS_REFERENCIA, $categoria);
    }

    public function getDependencies()
    {
        return [
            UsuariosFixtures::class
        ];
    }
}
