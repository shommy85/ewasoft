<?php

namespace App\Repository;

use App\Entity\PostUserLikes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostUserLikes>
 *
 * @method PostUserLikes|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostUserLikes|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostUserLikes[]    findAll()
 * @method PostUserLikes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostUserLikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostUserLikes::class);
    }

//    /**
//     * @return PostUserLikes[] Returns an array of PostUserLikes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostUserLikes
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
