<?php

namespace App\Repository;

use App\Entity\DisponibilidadVivienda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DisponibilidadVivienda>
 *
 * @method DisponibilidadVivienda|null find($id, $lockMode = null, $lockVersion = null)
 * @method DisponibilidadVivienda|null findOneBy(array $criteria, array $orderBy = null)
 * @method DisponibilidadVivienda[]    findAll()
 * @method DisponibilidadVivienda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisponibilidadViviendaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DisponibilidadVivienda::class);
    }

    //    /**
    //     * @return DisponibilidadVivienda[] Returns an array of DisponibilidadVivienda objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DisponibilidadVivienda
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
     public function findViviendasDisponiblesEntreFechas(\DateTimeInterface $fechaInicio, \DateTimeInterface $fechaFin)
    {
        return $this->createQueryBuilder('dv')
            ->innerJoin('dv.vivienda', 'v')
            ->where('dv.fecha BETWEEN :fechaInicio AND :fechaFin')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFin', $fechaFin)
            ->getQuery()
            ->getResult();
    }




    
}


