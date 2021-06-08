<?php

namespace App\Repository;

use App\Entity\Ville;
use App\Search\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    public function searchVille(Search $ville): array{

        $query = $this
            ->createQueryBuilder('v')
        ;

        if(!empty($ville->recherche)){
            $query = $query
                ->andWhere('v.nomVille LIKE :recherche')
                ->setParameter('recherche', "%{$ville->recherche}%");
        }

        return $query->getQuery()->getResult();
    }
}
