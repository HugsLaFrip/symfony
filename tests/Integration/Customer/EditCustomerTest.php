<?php

namespace App\Tests\integration\Customer;

use App\Repository\CustomerRepository;
use App\Tests\Factory\CustomerFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditCustomerTest extends WebTestCase
{
    public function testAnUnauthenticatedUserCanNotAccessEditForm()
    {
        $client = self::createClient();

        $customer = CustomerFactory::createOne();

        $client->request('GET', '/customers/' . $customer->id . '/edit');

        $this->assertResponseRedirects('/login');
    }

    public function testAModeratorCanNotAccessEditForm()
    {
        $client = self::createClient();

        $customer = CustomerFactory::createOne();

        $user = UserFactory::createOne([
            'roles' => ['ROLE_MODERATOR']
        ]);

        $client->loginUser($user->object());

        $client->request('GET', '/customers/' . $customer->id . '/edit');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testAUserCanNotEditACustomerFromAnOtherUser()
    {
        $client = self::createClient();

        $customer = CustomerFactory::createOne();

        $user = UserFactory::createOne();

        $client->loginUser($user->object());

        $client->request('GET', "/customers/$customer->id/edit");

        $this->assertResponseStatusCodeSame(403);
    }

    public function testAnAdminCanEditAnyCustomer()
    {
        $client = self::createClient();

        $customer = CustomerFactory::createOne();

        $user = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN']
        ]);

        $client->loginUser($user->object());

        $client->request('GET', "/customers/$customer->id/edit");

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $client->submitForm('Enregistrer', [
            'customer[firstName]' => 'fakeFirst',
            'customer[lastName]' => 'fakeLast',
            'customer[email]' => 'fake@mail.fr'
        ]);

        $updatedCustomer = CustomerFactory::find($customer->id);

        $this->assertEquals('fakeFirst', $updatedCustomer->firstName);
        $this->assertEquals('fakeLast', $updatedCustomer->lastName);
        $this->assertEquals('fake@mail.fr', $updatedCustomer->email);

        $this->assertResponseRedirects('/customers');
    }
}
