<?php

namespace App\Repository;

use App\Entity\Articulo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 * @method Articulo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articulo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articulo[]    findAll()
 * @method Articulo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticuloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articulo::class);
    }
//Para hacer operaciones con la base de datos se trabajan con los repositorios. Abstracciones. Comunica las entidades/objetos con la base de datos.
    public function findAll()
    {
        return $this->findBy(array(), array('fechaPublicacion' => 'DESC'));
    }


    // /**
    //  * @return Articulo[] Returns an array of Articulo objects
    //  */
    
    public function mostrarArticulosPorCategoria ($categoria)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.categoria = :categoria')
            ->setParameter('categoria', $categoria)
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Articulo
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
