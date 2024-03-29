<?php

namespace App\Repository;

use App\Entity\RssSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RssSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method RssSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method RssSource[]    findAll()
 * @method RssSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RssSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RssSource::class);
    }

    // /**
    //  * @return Source[] Returns an array of Source objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Source
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
