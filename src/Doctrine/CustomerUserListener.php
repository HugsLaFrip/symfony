<?php

namespace App\Doctrine;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Security;

class CustomerUserListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Customer $customer)
    {
        if (empty($customer->user)) {
            $customer->user = $this->security->getUser();
        }
    }
}
