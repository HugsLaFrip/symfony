<?php

namespace App\Controller;

use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerCreationController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    /**
     * @Route("/customers/create", name="customers_create")
     * @IsGranted("CAN_CREATE_CUSTOMER")
     * @return Response
     */
    public function displayForm(Request $request): Response
    {
        $form = $this->createForm(CustomerType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer = $form->getData();

            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le client a bien été enregistré');

            return $this->redirectToRoute("customers_list");
        }

        return $this->render("customers/create.html.twig", [
            "titre" => "Formulaire de création de clients",
            'form' => $form->createView()
        ]);
    }
}
