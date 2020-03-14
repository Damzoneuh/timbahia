<?php

namespace App\Repository;

use App\Entity\AssociationHourly;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AssociationHourly|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssociationHourly|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssociationHourly[]    findAll()
 * @method AssociationHourly[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationHourlyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssociationHourly::class);
    }

    // /**
    //  * @return AssociationHourly[] Returns an array of AssociationHourly objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssociationHourly
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
