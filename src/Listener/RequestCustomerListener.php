<?php

namespace App\Listener;

use App\Repository\CustomerRepository;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestCustomerListener
{
    private $repository;

    public function __construct(CustomerRepository $customerRepo)
    {
        $this->repository = $customerRepo;
    }

    public function addCustomerToRequest(RequestEvent $event)
    {
        dd($event);
        $request = $event->getRequest();

        $route = $request->attributes->get('_route');

        if ($route !== 'customers_edit') {
            return;
        }

        $id = $request->attributes->get('id');

        $customer = $this->repository->find($id);

        $request->attributes->set('myCustomer', $customer);
    }
}
