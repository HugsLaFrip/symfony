<?php

namespace App\Tests\Integration\Invoice;

use App\Tests\Factory\UserFactory;
use App\Tests\Factory\CustomerFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateInvoice extends WebTestCase
{
    public function testAnAuthenticatedUserCanCreateAnInvoice()
    {
        $client = self::createClient();

        $user = UserFactory::createOne();

        $client->loginUser($user->object());

        $count = mt_rand(5, 10);

        $customers = CustomerFactory::createMany($count, [
            'user' => $user
        ]);

        CustomerFactory::createMany(10);

        $crawler = $client->request('GET', '/invoices/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('select');

        $this->assertEquals($count, $crawler->filter('select option')->count());

        $client->submitForm('Enregistrer', [
            'invoice[amount]' => 299.99,
            'invoice[customer]' => $customers[0]->id
        ]);

        $this->assertCount(1, $customers[0]->invoices);

        $invoice = $customers[0]->invoices[0];

        $this->assertEquals(29999, $invoice->amount);
        $this->assertNotNull($invoice->createdAt);
    }
}
