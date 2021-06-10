<?php

namespace App\Tests\Integration\Entity;

use App\Tests\Factory\CustomerFactory;
use App\Tests\Factory\InvoiceFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceTest extends KernelTestCase
{
    public function testWeCanPersistAndFlushAnInvoice()
    {
        self::bootKernel();

        $invoice = InvoiceFactory::createOne();

        $this->assertNotNull($invoice);
    }

    public function testWeCanAccessACustomersInvoices()
    {
        self::bootKernel();

        $customer = CustomerFactory::createOne();

        InvoiceFactory::createMany(5, [
            'customer' => $customer
        ]);

        $this->assertCount(5, $customer->invoices);
    }
}
