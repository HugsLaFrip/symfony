<?php

namespace App\Controller\Invoice;

use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceListController extends AbstractController
{
    /**
     * @Route("/invoices", name="invoices_index")
     */
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        $invoices = $invoiceRepository->findInvoicesForUser($this->getUser());

        return $this->render('invoices/index.html.twig', [
            'invoices' => $invoices
        ]);
    }
}
