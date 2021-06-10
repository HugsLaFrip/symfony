<?php

namespace App\Tests\Integration\Invoice;

use App\Tests\Factory\CustomerFactory;
use App\Tests\Factory\InvoiceFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListInvoiceTest extends WebTestCase
{
    public function testAnAuthenticatedUserCanSeeTheInvoicesOfHisCustomers()
    {
        $client = self::createClient();

        $user = UserFactory::createOne()->object();

        $client->loginUser($user);

        $customers = CustomerFactory::createMany(5, [
            'user' => $user
        ]);

        $invoices = InvoiceFactory::createMany(5, [
            'customer' => $customers[0]
        ]);

        $client->request('GET', '/invoices');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }
}
