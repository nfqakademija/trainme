<?php

namespace App\Repository;

use App\Entity\ScheduledWorkout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScheduledWorkout|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduledWorkout|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduledWorkout[]    findAll()
 * @method ScheduledWorkout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduledWorkoutRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScheduledWorkout::class);
    }

    // /**
    //  * @return ScheduledWorkout[] Returns an array of ScheduledWorkout objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScheduledWorkout
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
