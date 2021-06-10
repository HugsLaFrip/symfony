<?php

namespace App\Tests\Integration\Invoice;

use App\Tests\Factory\CustomerFactory;
use App\Tests\Factory\InvoiceFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteInvoiceTest extends WebTestCase
{
    public function testAnAuthenticatedUserCanDeleteAnInvoice()
    {
        $client = self::createClient();

        $user = UserFactory::createOne()->object();

        $client->loginUser($user);

        $customer = CustomerFactory::createOne([
            'user' => $user
        ]);

        $invoice = InvoiceFactory::createOne([
            'customer' => $customer->object()
        ]);

        $client->request('GET', "/invoices/$invoice->id/delete");

        $this->assertResponseRedirects('/invoices');

        $deletedInvoice = InvoiceFactory::findBy([
            'id' => $invoice->id
        ])[0] ?? null;

        $this->assertNull($deletedInvoice);
    }
}
