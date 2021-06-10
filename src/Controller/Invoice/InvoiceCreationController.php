<?php

namespace App\Controller\Invoice;

use App\Form\InvoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceCreationController extends AbstractController
{
    /**
     * @Route("/invoices/create", name="invoices_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(InvoiceType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($form->getData());
            $em->flush();
        }

        return $this->render('invoices/create.html.twig', [
            'form' => $form->createView(),
            'customers' => $this->getUser()->customers
        ]);
    }
}
