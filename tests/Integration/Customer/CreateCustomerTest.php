<?php

namespace App\Tests\integration\Customer;

use App\Repository\CustomerRepository;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateCustomerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testAnUnauthenticatedUserCanNotCreateACustomer()
    {
        $this->client->request('GET', '/customers/create');

        $this->assertResponseRedirects('/login');
    }

    public function testAModeratorCanNotCreateACustomer()
    {
        $user = UserFactory::createOne([
            'roles' => ['ROLE_MODERATOR']
        ]);

        $this->client->loginUser($user->object());

        $this->client->request('GET', '/customers/create');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testAnAuthenticatedUserCanCreateCustomer()
    {
        $user = UserFactory::createOne();

        $this->client->loginUser($user->object());

        $this->client->request('GET', '/customers/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $this->client->submitForm('Enregistrer', [
            'customer[firstName]' => 'Jérôme',
            'customer[lastName]' => 'Dupont',
            'customer[email]' => 'j.dupont@mail.fr'
        ]);

        /**
         * @var CustomerRepository
         */
        $customerRepository = self::$container->get(CustomerRepository::class);

        $customer = $customerRepository->findOneBy([
            'firstName' => 'Jérôme',
            'lastName' => 'Dupont',
            'email' => 'j.dupont@mail.fr'
        ]);

        $this->assertNotNull($customer);
        $this->assertResponseRedirects('/customers');
    }
}
