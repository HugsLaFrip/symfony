<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CustomerDeleteController extends AbstractController
{
    private $customerRepository;

    private $entityManager;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $em)
    {
        $this->customerRepository = $customerRepository;
        $this->entityManager = $em;
    }

    /**
     * @Route("/customers/{id}/delete", name="customers_delete")
     */
    public function delete(Request $request): Response
    {
        $id = $request->attributes->get("id");

        $customer = $this->customerRepository->find($id);

        if (!$customer) {
            throw new NotFoundHttpException("Le client n°$id n'existe pas");
        }

        $this->entityManager->remove($customer);
        $this->entityManager->flush();

        return $this->redirectToRoute('customers_list');
    }
}
