<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Customer::class);
    }

    public function findBestCustomers(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select("CONCAT(c.firstName, ' ', c.lastName) AS fullName, SUM(i.amount) AS total")
            ->join('c.invoices', 'i')
            ->where('c.user = :user')
            ->setParameter(':user', $user)
            ->groupBy('c.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}
