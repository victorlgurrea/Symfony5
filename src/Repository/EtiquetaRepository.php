<?php

namespace App\Repository;

use App\Entity\Etiqueta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Etiqueta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etiqueta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etiqueta[]    findAll()
 * @method Etiqueta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtiquetaRepository extends ServiceEntityRepository
{
    private $user;
    public function __construct(Security $security, ManagerRegistry $registry)
    {
        parent::__construct($registry, Etiqueta::class);
        $this->user = $security->getUser();
    }

    public function buscarPorNombre($nombre)
    {
        return $this->createQueryBuilder('e')
            ->select('e.id, e.nombre as text')
            ->andWhere('e.nombre LIKE :nombre')
            ->andWhere('e.usuario = :usuario')
            ->setParameter('nombre', '%' . $nombre . '%')
            ->setParameter('usuario', $this->user)
            ->orderBy('e.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
