<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\Decision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    public function save(Comments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comments $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCommentsBeforeFirstDecision(Decision $decision): array
    {
        if ($decision->getStatus()->getName() !== 'Decision started') {
            return $this->createQueryBuilder('c')
                ->where('c.commentType = :type')
                ->andWhere('c.decision = :decision')
                ->andWhere('c.createdAt < :date')
                ->setParameters([
                    'type' => 'Comment',
                    'decision' => $decision->getId(),
                    'date' => $decision->getFirstDecisionAt()
                ])
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('c')
                ->where('c.commentType = :type')
                ->andWhere('c.decision = :decision')
                ->setParameters([
                    'type' => 'Comment',
                    'decision' => $decision->getId(),
                ])
                ->getQuery()
                ->getResult();
        }
    }

    public function findOpinionsBeforeFirstDecision(Decision $decision): array
    {
        if ($decision->getStatus()->getName() !== 'Decision started') {
            return $this->createQueryBuilder('c')
                ->where('c.commentType = :type')
                ->andWhere('c.decision = :decision')
                ->andWhere('c.createdAt < :date')
                ->setParameters([
                    'type' => 'Opinion',
                    'decision' => $decision->getId(),
                    'date' => $decision->getFirstDecisionAt()
                ])
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('c')
                ->where('c.commentType = :type')
                ->andWhere('c.decision = :decision')
                ->setParameters([
                    'type' => 'Opinion',
                    'decision' => $decision->getId(),
                ])
                ->getQuery()
                ->getResult();
        }
    }

    public function findCommentsOnFirstDecision(Decision $decision): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.commentType = :type')
            ->andWhere('c.decision = :decision')
            ->andWhere('c.createdAt > :date')
            ->setParameters([
                'type' => 'Comment',
                'decision' => $decision->getId(),
                'date' => $decision->getFirstDecisionAt()
            ])
            ->getQuery()
            ->getResult();
    }

    public function findOpinionsOnFirstDecision(Decision $decision): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.commentType = :type')
            ->andWhere('c.decision = :decision')
            ->andWhere('c.createdAt > :date')
            ->setParameters([
                'type' => 'Opinion',
                'decision' => $decision->getId(),
                'date' => $decision->getFirstDecisionAt()
            ])
            ->getQuery()
            ->getResult();
    }

    public function findCommentsOnFinalDecision(Decision $decision): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.commentType = :type')
            ->andWhere('c.decision = :decision')
            ->andWhere('c.createdAt > :date')
            ->setParameters([
                'type' => 'Comment',
                'decision' => $decision->getId(),
                'date' => $decision->getFinalDecisionAt()
            ])
            ->getQuery()
            ->getResult();
    }

    public function findOpinionsOnFinalDecision(Decision $decision): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.commentType = :type')
            ->andWhere('c.decision = :decision')
            ->andWhere('c.createdAt > :date')
            ->setParameters([
                'type' => 'Opinion',
                'decision' => $decision->getId(),
                'date' => $decision->getFinalDecisionAt()
            ])
            ->getQuery()
            ->getResult();
    }
}
