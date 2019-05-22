<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    // /**
    //  * @return Expense[] Returns an array of Expense objects
    //  */
    
    public function findByUser($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :val')
            ->setParameter('val', $value)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function SumAll($value)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :val')
            ->setParameter('val', $value)
            ->select('SUM(e.amout) as amout')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    
    public function findByUserOneMonth($value1, $value2, $value3)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :val')
            ->setParameter('val', $value1)
            ->andWhere('e.date >= :val2')
            ->setParameter('val2', $value2)
            ->andWhere('e.date <= :val3')
            ->setParameter('val3', $value3)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function SumOneMonth($value1, $value2, $value3)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :val')
            ->setParameter('val', $value1)
            ->andWhere('e.date >= :val2')
            ->setParameter('val2', $value2)
            ->andWhere('e.date <= :val3')
            ->setParameter('val3', $value3)
            //->orderBy('e.date', 'ASC')
            ->select('SUM(e.amout) as amout')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    
    public function findBySearchParamAll( $user, $startDate, $endDate, $desc, $category)
    {
         $qb = $this->createQueryBuilder('e')
            ->where('e.user = :val')
            ->setParameter('val', $user);
            if($startDate != ''){$qb
            ->andWhere('e.date >= :val2')
            ->setParameter('val2', $startDate);
            }
            if($endDate != ''){$qb
            ->andWhere('e.date <= :val3')
            ->setParameter('val3', $endDate);
            }
            if($desc != ''){$qb
            ->andWhere('e.description = :val4')
            ->setParameter('val4', $desc);
            }
            if($category != ''){$qb
            ->andWhere('e.category = :val5')
            ->setParameter('val5', $category);
            }
            $qb
            ->orderBy('e.date', 'DESC')
            ;
        return $qb->getQuery()->getResult();
    }

    public function SumForSearchParamAll( $user, $startDate, $endDate, $desc, $category)
    {
        $qb = $this->createQueryBuilder('e')
        ->where('e.user = :val')
        ->setParameter('val', $user);
        if($startDate != ''){$qb
        ->andWhere('e.date >= :val2')
        ->setParameter('val2', $startDate);
        }
        if($endDate != ''){$qb
        ->andWhere('e.date <= :val3')
        ->setParameter('val3', $endDate);
        }
        if($desc != ''){$qb
        ->andWhere('e.description = :val4')
        ->setParameter('val4', $desc);
        }
        if($category != ''){$qb
        ->andWhere('e.category = :val5')
        ->setParameter('val5', $category);
        }
        $qb
        //->orderBy('e.date', 'ASC')
        ->select('SUM(e.amout) as amout')
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    /*
    public function findOneBySomeField($value): ?Expense
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
