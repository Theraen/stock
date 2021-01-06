<?php

namespace App\Repository;

use App\Entity\PreparationRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PreparationRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreparationRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreparationRecipe[]    findAll()
 * @method PreparationRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreparationRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreparationRecipe::class);
    }

    // /**
    //  * @return PreparationRecipe[] Returns an array of PreparationRecipe objects
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
    public function findOneBySomeField($value): ?PreparationRecipe
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
