<?php

namespace App\Repository;

use App\Entity\Act;
use App\Entity\Spectacle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Spectacle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spectacle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spectacle[]    findAll()
 * @method Spectacle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpectacleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spectacle::class);
    }

    public function findNextSpectacle()
    {
        $qb = $this->createQueryBuilder('s');
        $query = $qb
            ->where($qb->expr()->gt('s.date', 'CURRENT_TIMESTAMP()'))
            ->orderBy('s.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery();
        return $query->getSingleResult();
    }

    public function findNextSpectacles(): array
    {
        $qb = $this->createQueryBuilder('s');
        $query = $qb
            ->where($qb->expr()->gt('s.date', 'CURRENT_TIMESTAMP()'))
            ->orderBy('s.date', 'DESC')
            ->getQuery();
        return $query->getResult();
    }

    public function findPreviousSpectacles()
    {
        $qb = $this->createQueryBuilder('s');
        $query = $qb
            ->where($qb->expr()->lt('s.date', 'CURRENT_TIMESTAMP()'))
            ->orderBy('s.date', 'DESC')
            ->getQuery();
        return $query->getResult();
    }

    public function findNextSpectacleWithAct(Act $act)
    {
        $qb = $this->createQueryBuilder('s');
        $query = $qb
            ->where($qb->expr()->gt('s.date', 'CURRENT_TIMESTAMP()'))
            ->andWhere(':act MEMBER OF s.acts')
            ->setParameter('act', $act)
            ->orderBy('s.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery();
        return $query->getSingleResult();
    }
}
