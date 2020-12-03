<?php

namespace App\DataFixtures;

use App\Entity\Categoria;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriasFixtures extends Fixture
{
    public const CATEGORIA_TECNOLOGICAS_REFERENCIA = 'categoria.tecnologicas';

    public function load(ObjectManager $manager)
    {
        $categoria = new Categoria();
        $categoria->setNombre('tecnologicas');
        $categoria->setColor('orange');
        $manager->persist($categoria);

        $manager->flush();

        $this->addReference(self::CATEGORIA_TECNOLOGICAS_REFERENCIA, $categoria);
    }
}
