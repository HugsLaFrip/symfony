<?php

namespace App\Controller;

use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerEditionController extends AbstractController
{
    private $repository;

    private $entity;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $em)
    {
        $this->repository = $customerRepository;
        $this->entity = $em;
    }

    /**
     * @Route("/customers/{id}/edit", name="customers_edit")
     */
    public function edit(Request $request): Response
    {
        $id = $request->attributes->get('id');

        $customer = $this->repository->find($id);

        if (!$customer) {
            throw new NotFoundHttpException("Le client n°$id n'existe pas");
        }

        $form = $this->createForm(CustomerType::class, $customer)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entity->flush();

            return $this->redirectToRoute("customers_list");
        }

        return $this->render("customers/edit.html.twig", [
            'titre' => "Formulaire de modification d'un client",
            'form' => $form->createView()
        ]);
    }
}
