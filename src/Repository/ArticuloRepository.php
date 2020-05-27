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
    
    public function mostrarArticulosPorCategoria ($id_categoria)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.categoria = :categoria')
            ->setParameter('categoria', $id_categoria)
            ->orderBy('a.fechaPublicacion', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function mostrarArticulosPorIdioma ($id_idioma)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.idioma = :idioma')
            ->setParameter('idioma', $id_idioma)
            ->orderBy('a.fechaPublicacion', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function mostrarArticulosPorAutor ($id_autor)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.autor = :autor')
            ->setParameter('autor', $id_autor)
            ->orderBy('a.fechaPublicacion', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    public function mostrarArticulosPorCategoriaMasReciente ()
    {
        //Buscando el artículo más reciente por categoria: 
        /*Siendo 'a' una referencia a la tabla artículo:
        
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Articulo a
            INNER JOIN (
                SELECT id, MAX(fecha_publicacion) FROM App\Entity\Articulo GROUP BY categoria_id
                ) tmp //nombrando a la tabla con un alias provisional
                ON a.id = tmp.id;'
        );
        return $query->getResult();   
        */
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Articulo a
            WHERE a.id IN (SELECT MAX(b.id) FROM App\Entity\Articulo b GROUP BY b.categoria)'
        );
        return $query->getResult();   

    }
    public function mostrarTresArticulosNuevos ()
    {
        /*
        //Mostrar 3 artículos más recientes: 
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
        //Symfony no entiende la palabra LIMIT. 
        //Se debe usar un método de doctrine para limitar los resultados a mostrar.
            'SELECT * FROM articulo ORDER BY id DESC LIMIT 3' 
        );
        return $query->getResult();   
        */
        return $this->getEntityManager()
        ->createQuery('SELECT b FROM App\Entity\Articulo b ORDER BY b.id DESC')
        ->setMaxResults(3)
        ->getResult();

    }  
} 
