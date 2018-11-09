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
}
