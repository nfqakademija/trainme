<?php

namespace App\Repository;

use App\Entity\AvailabilitySlot;
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
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AvailabilitySlot::class);
    }
}
