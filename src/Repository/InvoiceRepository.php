<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Invoice::class);
    }

    public function findInvoicesForUser(
        User $user,
        int $limit = null,
        string $orderBy = null,
        string $direction = "ASC"
    ) {
        $queryBuilder =  $this->createQueryBuilder('i')
            ->join('i.customer', 'c')
            ->where('c.user = :user')
            ->setParameter(':user', $user);

        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        };

        if ($orderBy) {
            $queryBuilder->orderBy('i.' . $orderBy, $direction);
        }

        return $queryBuilder->getQuery()
            ->getResult();
    }

    public function findLastMonthsSales(User $user)
    {
        return $this->createQueryBuilder('i')
            ->select('SUM(i.amount) AS total, YEAR(i.createdAt) AS annee, MONTH(i.createdAt) AS mois')
            ->groupBy('annee, mois')
            ->orderBy('annee', 'DESC')
            ->addOrderBy('mois', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
