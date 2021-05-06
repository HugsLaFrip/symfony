<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerListController extends AbstractController
{
    private $repository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->repository = $customerRepository;
    }

    /**
     * @Route("/customers", name="customers_list")
     * @return Response
     */
    public function list(): Response
    {
        $customers = $this->repository->findAll();

        return $this->render("customers/list.html.twig", [
            "customers" => $customers
        ]);
    }
}
