<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stock>
 *
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    public function add(Stock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Stock $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Stock[] Returns an array of Stock objects
    */
   public function findByCompanyAndDates($companyId, $startDate, $endDate): array
   {
        $mainPeriod = $this->createQueryBuilder('s')
            ->innerJoin('s.Company', 'c')
            ->where('c.id = :companyId')
            ->setParameter('companyId', $companyId)

            ->andWhere('s.date >= :startDate')
            ->setParameter('startDate', $startDate)
            
            ->andWhere('s.date <= :endDate')
            ->setParameter('endDate', $endDate)
            
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;

        $daysCount = count($mainPeriod);

        $previousPeriod = $this->createQueryBuilder('s')
            ->innerJoin('s.Company', 'c')
            ->where('c.id = :companyId')
            ->setParameter('companyId', $companyId)

            ->andWhere('s.date < :startDate')
            ->setParameter('startDate', $startDate)
            
            ->orderBy('s.id', 'DESC')
            ->setMaxResults($daysCount)
            ->getQuery()
            ->getArrayResult();

        $nextPeriod = $this->createQueryBuilder('s')
            ->innerJoin('s.Company', 'c')
            ->where('c.id = :companyId')
            ->setParameter('companyId', $companyId)

            ->andWhere('s.date > :endDate')
            ->setParameter('endDate', $endDate)
            
            ->orderBy('s.id', 'ASC')
            ->setMaxResults($daysCount)
            ->getQuery()
            ->getArrayResult();

        return [
            'main_period' => $mainPeriod,
            'previous_period' => $previousPeriod,
            'next_period' => $nextPeriod,
        ];
   }

//    public function findOneBySomeField($value): ?Stock
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
