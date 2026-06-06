<?php

namespace App\Repository;

use App\Entity\Inventaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inventaire>
 */
class InventaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventaire::class);
    }

    //    /**
    //     * @return Inventaire[] Returns an array of Inventaire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

       public function inventaire()
       {
          return $this->createQueryBuilder('c')
        ->groupBy('c.date')
        ->getQuery()
        ->getResult();
       }

         public function mouvement($produit, $date1, $date2): array
    {
        $debut = new \Datetime($date1);
        // $debut = (clone $date)->setTime(0, 0, 0);

        $fin = new \Datetime($date2);
        // $fin = (clone $date)->setTime(23, 59, 59);

        return $this->createQueryBuilder('c')
            ->andWhere('c.date BETWEEN :debut AND :fin')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin)
            ->andWhere('c.produit = :produit')
            ->setParameter('produit', $produit)
            ->getQuery()
            ->getResult();
    }
}
