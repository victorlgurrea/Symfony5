<?php

namespace App\Repository;

use App\Entity\Marcador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Marcador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marcador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marcador[]    findAll()
 * @method Marcador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarcadorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marcador::class);
    }

    public function buscarCategoriaPorNombre(string $nombreCategoria)
    {
        return $this->createQueryBuilder('m')
                    ->innerJoin('m.categoria', 'c')
                    ->where('c.nombre =:nombreCategoria')
                    ->setParameter('nombreCategoria', $nombreCategoria)
                    ->getQuery()
                    ->getResult();
    }

    public function buscarPorNombre($nombre)
    {
        return $this->createQueryBuilder('m')
                    ->where('m.nombre LIKE :nombre')
                    ->setParameter('nombre', '%' . $nombre . '%')
                    ->getQuery()
                    ->getResult();
    }

    // /**
    //  * @return Marcador[] Returns an array of Marcador objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Marcador
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
