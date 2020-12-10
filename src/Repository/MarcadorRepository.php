<?php

namespace App\Repository;

use App\Entity\Marcador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Marcador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marcador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marcador[]    findAll()
 * @method Marcador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarcadorRepository extends ServiceEntityRepository
{
    public function __construct(Security $security, ManagerRegistry $registry)
    {
        parent::__construct($registry, Marcador::class);
        $this->usuario = $security->getUser();
    }

    public function buscarCategoriaPorNombre(string $nombreCategoria, $pagina = 1, $elementos_por_pagina = 5)
    {
        $query =  $this->createQueryBuilder('m')
                    ->innerJoin('m.categoria', 'c')
                    ->where('c.nombre =:nombreCategoria')
                    ->andWhere('m.usuario =:usuario')
                    ->setParameter('nombreCategoria', $nombreCategoria)
                    ->setParameter('usuario', $this->usuario)
                    ->orderBy('m.creado', 'DESC')
                    ->getQuery();
        
        return $this->paginacion($query, $pagina, $elementos_por_pagina);              
    }

    public function buscarPorNombre($nombre, $pagina = 1, $elementos_por_pagina = 5)
    {
        $query =   $this->createQueryBuilder('m')
                    ->where('m.nombre LIKE :nombre')
                    ->andWhere('m.usuario =:usuario')
                    ->setParameter('usuario', $this->usuario)
                    ->setParameter('nombre', '%' . $nombre . '%')
                    ->orderBy('m.creado', 'DESC')
                    ->getQuery();
                
        return $this->paginacion($query, $pagina, $elementos_por_pagina);  
    }

    public function buscarPorFavoritos($pagina = 1, $elementos_por_pagina = 5)
    {
        $query =   $this->createQueryBuilder('m')
                    ->where('m.favorito = true')
                    ->andWhere('m.usuario = :usuario')
                    ->setParameter('usuario', $this->usuario)
                    ->orderBy('m.creado', 'DESC')
                    ->getQuery();
                
        return $this->paginacion($query, $pagina, $elementos_por_pagina);  
    }
    
    public function buscarTodos($pagina = 1, $elementos_por_pagina = 5)
    {  
        $query =   $this->createQueryBuilder('m')
                    ->where('m.usuario =:usuario')
                    ->setParameter('usuario', $this->usuario)
                    ->orderBy('m.creado', 'DESC')
                    ->addOrderBy('m.nombre', 'ASC')
                    ->getQuery();

        return $this->paginacion($query, $pagina, $elementos_por_pagina);  
    }

    public function paginacion($dql, $pagina = 1, $elementos_por_pagina = 5)
    {
        $paginador = new Paginator($dql);
        $paginador->getQuery()
                    ->setFirstResult($elementos_por_pagina * ($pagina - 1))
                    ->setMaxResults($elementos_por_pagina);

        return $paginador;
    }

}
