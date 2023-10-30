<?php

namespace App\Repository;

use App\Entity\NbBooks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NbBooks>
 *
 * @method NbBooks|null find($id, $lockMode = null, $lockVersion = null)
 * @method NbBooks|null findOneBy(array $criteria, array $orderBy = null)
 * @method NbBooks[]    findAll()
 * @method NbBooks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NbBooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbBooks::class);
    }

//    /**
//     * @return NbBooks[] Returns an array of NbBooks objects
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

//    public function findOneBySomeField($value): ?NbBooks
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
