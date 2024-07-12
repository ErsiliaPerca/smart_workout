<?php

namespace App\Repository;

use App\Entity\ExerciseLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExerciseLog>
 */
class ExerciseLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciseLog::class);
    }

    //    /**
    //     * @return ExerciseLog[] Returns an array of ExerciseLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ExerciseLog
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
   /**
     * Find all exercise logs for a specific exercise and authenticated user.
     *
     * @param int $exerciseId
     * @param int $userId
     * @return ExerciseLog[]
     */
    public function findAllExerciseLogsForExerciseAndUser(int $exerciseId, int $userId, bool $isTrainer): array
    {
        $qb = $this->createQueryBuilder('el')
            ->join('el.workout', 'w')
            ->andWhere('el.exercise = :exerciseId')
            ->setParameter('exerciseId', $exerciseId);
        if (!$isTrainer) {
            $qb->andWhere('w.person = :userId')
                ->setParameter('userId', $userId);
        }
        return $qb->getQuery()->getResult();
    }

}
