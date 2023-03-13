<?php

namespace App\Repository;

use App\Entity\Decision;
use App\Entity\Status;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @extends ServiceEntityRepository<Decision>
 *
 * @method Decision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Decision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Decision[]    findAll()
 * @method Decision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decision::class);
    }

    public function save(Decision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Decision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByDecisionTitle(string $name): array
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->where('d.title LIKE :name')
            ->join('d.author', 'a')
            ->orWhere('a.firstname LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('d.title', 'ASC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function countHowManyDecisionsIn30Days(): array
    {
        $arrFinal = [];
        $date = new DateTimeImmutable();
        $dateLess30Days = $date->modify('-90 days');
        $queryBuilder1 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :dateLess30Days')
            ->andWhere('d.createdAt < :dateToday')
            ->join('d.status', 's')
            ->andWhere('s.name = :started')
            ->setParameters([
                'dateLess30Days' => $dateLess30Days,
                'dateToday' => $date,
                'started' => 'Decision started'
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arrFinal[] = $queryBuilder1->getResult();

        $queryBuilder2 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :dateLess30Days')
            ->andWhere('d.createdAt < :dateToday')
            ->join('d.status', 's')
            ->andWhere('s.name = :first')
            ->setParameters([
                'dateLess30Days' => $dateLess30Days,
                'dateToday' => $date,
                'first' => 'First decision'
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arrFinal[] = $queryBuilder2->getResult();

        $queryBuilder3 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :dateLess30Days')
            ->andWhere('d.createdAt < :dateToday')
            ->join('d.status', 's')
            ->andWhere('s.name = :conflict')
            ->setParameters([
                'dateLess30Days' => $dateLess30Days,
                'dateToday' => $date,
                'conflict' => 'Decision in conflict'
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arrFinal[] = $queryBuilder3->getResult();

        $queryBuilder4 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :dateLess30Days')
            ->andWhere('d.createdAt < :dateToday')
            ->join('d.status', 's')
            ->andWhere('s.name = :final')
            ->setParameters([
                'dateLess30Days' => $dateLess30Days,
                'dateToday' => $date,
                'final' => 'Final decision'
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arrFinal[] = $queryBuilder4->getResult();

        $queryBuilder5 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :dateLess30Days')
            ->andWhere('d.createdAt < :dateToday')
            ->join('d.status', 's')
            ->andWhere('s.name = :cancel')
            ->setParameters([
                'dateLess30Days' => $dateLess30Days,
                'dateToday' => $date,
                'cancel' => 'Decision cancelled'
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arrFinal[] = $queryBuilder5->getResult();

        $queryBuilder6 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :dateLess30Days')
            ->andWhere('d.createdAt < :dateToday')
            ->join('d.status', 's')
            ->andWhere('s.name = :taken')
            ->setParameters([
                'dateLess30Days' => $dateLess30Days,
                'dateToday' => $date,
                'taken' => 'Decision taken'
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arrFinal[] = $queryBuilder6->getResult();
        return $arrFinal;
    }


    public function decisionByTrimester(): array
    {
        $arr = [];
        $firstTrimestreS = date_create_immutable('2023-01-01 00:00:00');
        $firstTrimestreE = date_create_immutable('2023-03-31 00:00:00');
        $secondTrimestreS = date_create_immutable('2023-04-01 00:00:00');
        $secondTrimestreE = date_create_immutable('2023-06-30 00:00:00');
        $thirdTrimestreS = date_create_immutable('2023-07-01 00:00:00');
        $thirdTrimestreE = date_create_immutable('2023-09-30 00:00:00');
        $fourTrimestreS = date_create_immutable('2023-10-01 00:00:00');
        $fourTrimestreE = date_create_immutable('2023-12-31 00:00:00');

        $queryBuilder1 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :firstTrimestreS')
            ->andWhere('d.createdAt < :firstTrimestreE')
            ->setParameters([
                'firstTrimestreS' => $firstTrimestreS,
                'firstTrimestreE' => $firstTrimestreE,
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arr[] = $queryBuilder1->getResult();

        $queryBuilder2 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :secondeTrimestreS')
            ->andWhere('d.createdAt < :secondTrimestreE')
            ->setParameters([
                'secondeTrimestreS' => $secondTrimestreS,
                'secondTrimestreE' => $secondTrimestreE,
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arr[] = $queryBuilder2->getResult();

        $queryBuilder3 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :thirdTrimestreS')
            ->andWhere('d.createdAt < :thirdTrimestreE')
            ->setParameters([
                'thirdTrimestreS' => $thirdTrimestreS,
                'thirdTrimestreE' => $thirdTrimestreE,
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arr[] = $queryBuilder3->getResult();

        $queryBuilder4 = $this->createQueryBuilder('d')
            ->where('d.createdAt > :fourTrimestreS')
            ->andWhere('d.createdAt < :fourTrimestreE')
            ->setParameters([
                'fourTrimestreS' => $fourTrimestreS,
                'fourTrimestreE' => $fourTrimestreE,
            ])
            ->select('count(d.id)')
            ->getQuery();
        $arr[] = $queryBuilder4->getResult();

        return $arr;
    }

    public function findLateStartedDecision(EntityManagerInterface $manager, DateTimeImmutable $date): array
    {
        $dateLess30Days = $date->modify('-30 days');
        return $this->createQueryBuilder('d')
            ->where('d.status = :startedStatus')
            ->andWhere('d.createdAt < :date')
            ->setParameters([
                'startedStatus' => $manager->getRepository(Status::class)->findOneBy([
                    'name' => 'Decision started'])->getId(),
                'date' => $dateLess30Days,
            ])
            ->getQuery()
            ->getResult();
    }

    public function findLateFirstDecision(EntityManagerInterface $manager, DateTimeImmutable $date): array
    {
        $dateLess30Days = $date->modify('-30 days');
        return $this->createQueryBuilder('d')
            ->where('d.status = :firstStatus')
            ->andWhere('d.firstDecisionAt < :date')
            ->setParameters([
                'firstStatus' => $manager->getRepository(Status::class)->findOneBy([
                    'name' => 'First decision'])->getId(),
                'date' => $dateLess30Days,
            ])
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Decision[] Returns an array of Decision objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Decision
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
