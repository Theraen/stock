<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    
    public function findAllShortDlc()
    {
        return $this->createQueryBuilder('p')
            ->where('DATE_DIFF(p.dlc, CURRENT_DATE()) <= 3')
            ->andWhere('DATE_DIFF(p.dlc, CURRENT_DATE()) >= 0')
            ->andWhere('p.shortDlc = 0')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    
    public function findAllLimitDlc()
    {
        return $this->createQueryBuilder('p')
            ->where('DATE_DIFF(p.dlc, CURRENT_DATE()) < 0')
            ->andWhere('p.shortDlc = 0')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    
    public function findSearch($search)
    {
        $query = $this->createQueryBuilder('p')
            ->select('c', 'p', 'pi')
            ->join('p.category', 'c')
            ->join('p.pictureStock', 'pi');

            if(!empty($search->q)) {
                $query = $query
                    ->andWhere('p.name LIKE :q')
                    ->setParameter('q', "%{$search->q}%");
            }

            if(!empty($search->categories)) {
                $query = $query
                    ->andWhere('c.id IN (:categories)')
                    ->setParameter('categories', $search->categories);
            }
            
            $query =  $query->getQuery();
            return $this->paginator->paginate(
                $query,
                $search->page,
                12
            );
    }



    

    /*
    public function findOneBySomeField($value): ?Product
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
