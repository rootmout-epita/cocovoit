<?php

namespace App\Repository;

use App\Entity\EmailChecker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailChecker|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailChecker|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailChecker[]    findAll()
 * @method EmailChecker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailCheckerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailChecker::class);
    }

    // /**
    //  * @return EmailChecker[] Returns an array of EmailChecker objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailChecker
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
