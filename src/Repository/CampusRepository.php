<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Search\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Campus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campus[]    findAll()
 * @method Campus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }

    public function searchCampus(Search $search): array{

        $query = $this
            ->createQueryBuilder('c')
        ;

        if(!empty($search->recherche)){
            $query = $query
                ->andWhere('c.nomCampus LIKE :recherche')
                ->setParameter('recherche', "%{$search->recherche}%");
        }

        return $query->getQuery()->getResult();
    }

}
