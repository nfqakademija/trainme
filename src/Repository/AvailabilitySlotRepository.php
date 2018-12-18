<?php

namespace App\Repository;

use App\Entity\AvailabilitySlot;
use App\Entity\Trainer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AvailabilitySlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvailabilitySlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvailabilitySlot[]    findAll()
 * @method AvailabilitySlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvailabilitySlotRepository extends ServiceEntityRepository
{
    /**
     * AvailabilitySlotRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AvailabilitySlot::class);
    }

    /**
     * @param Trainer $trainer
     * @return mixed
     */
    public function getTrainerSlotsWithScheduledWorkoutsArray(Trainer $trainer)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "select a, s from App\Entity\AvailabilitySlot a 
                 LEFT JOIN App\Entity\ScheduledWorkout s 
                 WITH s.trainer = a.trainer AND s.startsAt >= a.startsAt AND s.endsAt <= a.endsAt 
                 WHERE a.trainer = :trainer AND a.endsAt > CURRENT_TIMESTAMP() 
                 ORDER BY a.id, s.startsAt"
        )->setParameter('trainer', $trainer);

        $result = $query->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);

        return $result;
    }
}
