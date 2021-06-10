<?php

namespace App\Controller\Invoice;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceDeleteController extends AbstractController
{
    /**
     * @Route("/invoices/{id}/delete", name="invoices_delete")
     */
    public function delete(Invoice $invoice, EntityManagerInterface $em): Response
    {
        $em->remove($invoice);
        $em->flush();

        return $this->redirectToRoute("invoices_index");
    }
}
