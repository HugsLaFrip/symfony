<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerEditionController extends AbstractController
{
    private $entity;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entity = $em;
    }

    /**
     * @Route("/customers/{id}/edit", name="customers_edit")
     * @IsGranted("CAN_EDIT", subject="customer")
     */
    public function edit(Request $request, Customer $customer): Response
    {
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
