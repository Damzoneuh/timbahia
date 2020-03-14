<?php

namespace App\Repository;

use App\Entity\AssociationDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AssociationDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssociationDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssociationDetails[]    findAll()
 * @method AssociationDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssociationDetails::class);
    }

    // /**
    //  * @return AssociationDetails[] Returns an array of AssociationDetails objects
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
    public function findOneBySomeField($value): ?AssociationDetails
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
