<?php

namespace App\Repository;

use App\Entity\PictureStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method PictureStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method PictureStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method PictureStock[]    findAll()
 * @method PictureStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureStockRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, PictureStock::class);
        $this->paginator = $paginator;
    }

        /**
     * @return PictureStock[] Returns an array of PictureStock objects
     */
    
    public function findSearch($search, $user)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.user = :u')
            ->setParameter('u', $user);

            if(!empty($search->q)) {
                $query = $query
                    ->andWhere('p.name LIKE :q')
                    ->setParameter('q', "%{$search->q}%");
            }
            
            $query =  $query->getQuery();
            return $this->paginator->paginate(
                $query,
                $search->page,
                12
            );
    }

    /**
     * @return PictureStock[] Returns an array of PictureStock objects
     */
    
    public function findByUser($user)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :u')
            ->setParameter('u', $user)
            ->getQuery()
            ->getResult();

    }
}
