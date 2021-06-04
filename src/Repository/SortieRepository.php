<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Search\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, Sortie::class);
    }

    public function searchFilter(Search $search): array
    {
        $date =new \DateTime();
        $date->sub(new \DateInterval('P1M'));

        $query = $this
            ->createQueryBuilder('s')
            ->select('c', 's')
            ->join('s.campusOrganisateur', 'c')
            ->andWhere('s.dateHeureDebut > :date')
            ->setParameter('date', $date);

        if(!empty($search->campusSearch)){
            $query = $query
                ->andWhere('c.id IN (:campus)')
                ->setParameter('campus', $search->campusSearch);
        }

        if(!empty($search->recherche)){
            $query = $query
                ->andWhere('s.nomSortie LIKE :recherche')
                ->setParameter('recherche', "%{$search->recherche}%");
        }

        if(!empty($search->dateDebutSearch)){
            $query = $query
                ->andWhere('s.dateHeureDebut >= :debut')
                ->setParameter('debut', $search->dateDebutSearch);
        }

        if(!empty($search->dateFinSearch)){
            $query = $query
                ->andWhere('s.dateHeureDebut <= :fin')
                ->setParameter('fin', $search->dateFinSearch);
        }

        if(!empty($search->sortieOrganisee)){
            $query = $query
                ->andWhere('s.organisateur = :id')
                ->setParameter('id', 2);
        }

        if(!empty($search->sortiePassee)){
            $query = $query
                ->andWhere('s.etat = 5');
        }



        return $query->getQuery()->getResult();
    }

}
