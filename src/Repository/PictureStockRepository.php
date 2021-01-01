<?php

namespace App\Repository;

use App\Entity\PictureStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PictureStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method PictureStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method PictureStock[]    findAll()
 * @method PictureStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PictureStock::class);
    }

    // /**
    //  * @return PictureStock[] Returns an array of PictureStock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PictureStock
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
