<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * @return array
     */
    public function findLatest() : array {
        return $this->createQueryBuilder('t')
            ->setMaxResults(10)
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $depart : Lieu de départ du voyage
     * @param string $arrive : Lieu d'arrivé du voyage
     * @param date $dateDepart : Date de départ du voyage
     * @return array
     */
    public function findTrip($depart, $arrive, $dateDepart) : array {
        return $this->createQueryBuilder('t')
            ->where("t.departure_place LIKE '$depart%'")
            ->andWhere("t.arrival_place LIKE '$arrive%'")
//            ->where("t.departure_schedule = $dateDepart")
            ->andWhere("t.nbr_places > 0")
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Trip[] Returns an array of Trip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trip
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
