<?php

namespace App\Repository;

use App\Entity\Nationality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;

/**
 * @extends ServiceEntityRepository<Nationality>
 *
 * @method Nationality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nationality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nationality[]    findAll()
 * @method Nationality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NationalityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nationality::class);
    }

    public function save(Nationality $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Nationality $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
//    public function decisionOnFrenchHub(string $name)
//    {
//        $french = 'french';
//        $name = 'admin';
//        $queryBuilder = $this->createQueryBuilder('n')
//            ->innerJoin('n.users', 'u')
//            ->innerJoin('u.decisions', 'd')
//            ->where('n.name = :french')
////            ->andWhere('d.title = :name')
////            ->orWhere('n.name = :french')
//            ->andWhere('u.firstname = :name')
//            ->setParameters([
//                'name' => $name,
//                'french' => $french
//            ])
//            ->getQuery();
//        dd($queryBuilder->getSQL());
//
//        return $queryBuilder->getDQL();
//    }

//    /**
//     * @return Nationality[] Returns an array of Nationality objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Nationality
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
