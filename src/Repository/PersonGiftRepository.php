<?php

namespace App\Repository;

use App\Entity\PersonGift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonGift|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonGift|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonGift[]    findAll()
 * @method PersonGift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonGiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonGift::class);
    }

    // /**
    //  * @return PersonGift[] Returns an array of PersonGift objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PersonGift
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
