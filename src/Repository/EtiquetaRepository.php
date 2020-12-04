<?php

namespace App\Repository;

use App\Entity\Etiqueta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etiqueta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etiqueta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etiqueta[]    findAll()
 * @method Etiqueta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtiquetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etiqueta::class);
    }

    public function buscarPorNombre($nombre)
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.nombre as text')
            ->andWhere('e.nombre LIKE :nombre')
            ->setParameter('val', $nombre)
            ->orderBy('e.nombre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Etiqueta[] Returns an array of Etiqueta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etiqueta
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
