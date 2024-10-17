<?php

namespace App\Repository;

use App\Entity\API;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<API>
 */
class APIRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, API::class);
    }

    public function getRandomApi()
    {
        $apis = $this->findAll();

        if (empty($apis)) {
            return null;
        }

        $randomIndex = array_rand($apis);

        return $apis[$randomIndex];
    }

    //    /**
    //     * @return API[] Returns an array of API objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?API
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllByDESC()
    {
        return $this->findBy(array(), array('createdBy' => 'DESC'));
    }
}
