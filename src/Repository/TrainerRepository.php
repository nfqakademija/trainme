<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Trainer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Trainer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trainer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trainer[]    findAll()
 * @method Trainer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trainer::class);
    }

    public function getNotEvaluatedTrainers(Customer $customer)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "select t from App\Entity\Trainer t 
                 INNER JOIN App\Entity\ScheduledWorkout s
                 WITH s.trainer = t AND  s.customer = :customer AND s.endsAt < CURRENT_TIMESTAMP() 
                 LEFT JOIN App\Entity\Rating r
                 WITH r.trainer = s.trainer AND r.customer = :customer
                 WHERE r.id IS NULL
                 ORDER BY s.endsAt ASC"
        )->setParameter('customer', $customer);

        $result = $query->getResult();

        return $result;
    }

    /**
     * @param  int $page
     * @param  int $itemsPerPage
     * @param  null|string $name
     * @param  \DateTimeInterface|null $startsAt
     * @param  \DateTimeInterface|null $endsAt
     * @param  array $tags
     * @return Paginator
     */
    public function findFilteredTrainers(
        int $page,
        int $itemsPerPage,
        ?string $name,
        ?\DateTimeInterface $startsAt,
        ?\DateTimeInterface $endsAt,
        array $tags
    ): Paginator {
        $qb = $this->createQueryBuilder('t');
        if ($startsAt && $endsAt) {
            $qb->innerJoin('t.availabilitySlots', 'a', Join::WITH, $qb->expr()->andX(
                $qb->expr()->lte('a.startsAt', ':from'),
                $qb->expr()->gte('a.endsAt', ':to')
            ))
                ->leftJoin('t.scheduledWorkouts', 's', Join::WITH, $qb->expr()->orX(
                    $qb->expr()->andX(
                        $qb->expr()->gte('s.startsAt', ':from'),
                        $qb->expr()->lte('s.startsAt', ':to')
                    ),
                    $qb->expr()->andX(
                        $qb->expr()->gte('s.endsAt', ':from'),
                        $qb->expr()->lte('s.endsAt', ':to')
                    ),
                    $qb->expr()->andX(
                        $qb->expr()->gte('s.startsAt', ':from'),
                        $qb->expr()->lte('s.endsAt', ':to')
                    ),
                    $qb->expr()->andX(
                        $qb->expr()->lte('s.startsAt', ':from'),
                        $qb->expr()->gte('s.endsAt', ':to')
                    )
                ))->where(
                    $qb->expr()->isNull('s.id')
                )->setParameters(['from'=> $startsAt, 'to' => $endsAt]);
        }
        if ($name) {
            $qb->andWhere('t.name LIKE :name')->setParameter('name', '%' . $name . '%');
        }

        if (!empty($tags)) {
            $qb->innerJoin('t.tags', 'f')
                ->andWhere($qb->expr()->in('f.id', $tags));
        }

        $query = $qb->getQuery();
        $paginator = $this->paginate($query, $page, $itemsPerPage);
        return $paginator;
    }

    /**
     * Paginator Helper
     *
     * Pass through a query object, current page & limit
     * the offset is calculated from the page and limit
     * returns an `Paginator` instance, which you can call the following on:
     *
     *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
     *     $paginator->count() # Count of ALL posts (ie: `20` posts)
     *     $paginator->getIterator() # ArrayIterator
     *
     * @param \Doctrine\ORM\Query $dql DQL Query Object
     * @param integer $page Current page (defaults to 1)
     * @param integer $limit The total number per page (defaults to 5)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function paginate($dql, $page = 1, $limit = 6)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))// Offset
            ->setMaxResults($limit);

        return $paginator;
    }
}
