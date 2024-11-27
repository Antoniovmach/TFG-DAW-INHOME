<?php

namespace App\Repository;

use App\Entity\ViviendaFoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ViviendaFoto>
 *
 * @method ViviendaFoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViviendaFoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViviendaFoto[]    findAll()
 * @method ViviendaFoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViviendaFotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViviendaFoto::class);
    }

    //    /**
    //     * @return ViviendaFoto[] Returns an array of ViviendaFoto objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ViviendaFoto
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
