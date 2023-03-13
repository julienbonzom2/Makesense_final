<?php

namespace App\Repository;

use App\Entity\OngoingDecision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OngoingDecision>
 *
 * @method OngoingDecision|null find($id, $lockMode = null, $lockVersion = null)
 * @method OngoingDecision|null findOneBy(array $criteria, array $orderBy = null)
 * @method OngoingDecision[]    findAll()
 * @method OngoingDecision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OngoingDecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OngoingDecision::class);
    }

    public function save(OngoingDecision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OngoingDecision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return OngoingDecision[] Returns an array of OngoingDecision objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OngoingDecision
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
