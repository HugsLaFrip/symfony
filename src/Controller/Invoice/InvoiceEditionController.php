<?php

namespace App\Controller\Invoice;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceEditionController extends AbstractController
{
    /**
     * @Route("/invoices/{id}/edit", name="invoices_edit")
     */
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('invoices_index');
        }

        return $this->render('invoices/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
