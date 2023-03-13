<?php

namespace App\Repository;

use App\Entity\OngoingAssociated;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OngoingAssociated>
 *
 * @method OngoingAssociated|null find($id, $lockMode = null, $lockVersion = null)
 * @method OngoingAssociated|null findOneBy(array $criteria, array $orderBy = null)
 * @method OngoingAssociated[]    findAll()
 * @method OngoingAssociated[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OngoingAssociatedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OngoingAssociated::class);
    }

    public function save(OngoingAssociated $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OngoingAssociated $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    public function getImpacteds($decisionId): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.ongoing_decision_id = :val')
//            ->setParameter('val', $decisionId)
//            ->getQuery()
//            ->getResult();
//    }

//    /**
//     * @return OngoingAssociated[] Returns an array of OngoingAssociated objects
//     */
//    public function findByExampleField($value): array
//    {
//     return $this->createQueryBuilder('o')
////            ->andWhere('o.exampleField = :val')
////            ->setParameter('val', $value)
////            ->orderBy('o.id', 'ASC')
////            ->setMaxResults(10)
////            ->getQuery()
////            ->getResult()
//    }

//    public function findOneBySomeField($value): ?OngoingAssociated
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
