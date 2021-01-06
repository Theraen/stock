<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    private $paginator;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipe::class);
        $this->paginator = $paginator;
    }

    /**
     * @return Recipe[] Returns an array of Recipe objects
     */
    
    public function findSearch($search, $user)
    {
        $query = $this->createQueryBuilder('r')
            ->select('c', 'r', 'i')
            ->join('r.categoryRecipes', 'c')
            ->join('r.ingredients', 'i')
            ->andWhere('r.user = :u')
            ->setParameter('u', $user);

            if(!empty($search->q)) {
                $query = $query
                    ->andWhere('r.name LIKE :q')
                    ->setParameter('q', "%{$search->q}%");
            }

            if(!empty($search->categoriesRecipe)) {
                $query = $query
                    ->andWhere('c.id IN (:categoriesRecipe)')
                    ->setParameter('categoriesRecipe', $search->categoriesRecipe);
            }
            
            $query =  $query->getQuery();
            return $this->paginator->paginate(
                $query,
                $search->page,
                12
            );
    }
}
