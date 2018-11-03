<?php

namespace App\Repository;

use App\Entity\RepLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RepLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepLog[]    findAll()
 * @method RepLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepLogRepository extends ServiceEntityRepository {
  public function __construct(RegistryInterface $registry) {
    parent::__construct($registry, RepLog::class);
  }

  public function getLeaderboardDetails(){
	  return $this->createQueryBuilder('rl')
		  ->select('IDENTITY(rl.user) as user_id, SUM(rl.totalWeightLifted) as weightSum')
		  ->groupBy('rl.user')
		  ->orderBy('weightSum', 'DESC')
		  ->getQuery()
		  ->getResult();
  }

//    /**
//     * @return RepLog[] Returns an array of RepLog objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RepLog
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
