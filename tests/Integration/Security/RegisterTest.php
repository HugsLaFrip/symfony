<?php

namespace App\Tests\integration\Security;

use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Security;

class RegisterTest extends WebTestCase
{
    public function testAUserCanRegister()
    {
        $client = self::createClient();

        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $client->submitForm('Créer son compte', [
            'register[firstName]' => 'fakeFirst',
            'register[lastName]' => 'fakeLast',
            'register[email]' => 'fakeEmail@mail.fr',
            'register[password]' => 'password'
        ]);

        $this->assertResponseRedirects('/login');

        $createdUser = UserFactory::findBy([
            'firstName' => 'fakeFirst',
            'email' => 'fakeEmail@mail.fr'
        ])[0] ?? null;

        $this->assertNotNull($createdUser);

        $this->assertEquals('fakeFirst', $createdUser->firstName);
        $this->assertEquals('fakeLast', $createdUser->lastName);

        $this->assertNotEquals('password', $createdUser->password);
    }

    public function testARegisteredUserCanLogin()
    {
        $client = self::createClient();

        $user = UserFactory::createOne([
            'email' => 'fake@mail.fr',
            'password' => 'password'
        ]);

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $client->submitForm('Connexion', [
            'form[email]' => 'fake@mail.fr',
            'form[password]' => 'password'
        ]);

        $this->assertResponseRedirects('/customers');

        /**
         * @var Security
         */
        $security = self::$container->get(Security::class);

        $loggedIn = $security->getUser();

        $this->assertEquals($user->firstName, $loggedIn->firstName);
        $this->assertEquals($user->lastName, $loggedIn->lastName);
        $this->assertEquals($user->email, $loggedIn->email);
        $this->assertEquals($user->password, $loggedIn->password);
    }

    public function testItWillNotLoginBadCredentials()
    {
        $client = self::createClient();

        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $client->submitForm('Connexion', [
            'form[email]' => 'fake@mail.fr',
            'form[password]' => 'password'
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert-danger');

        /**
         * @var Security
         */
        $security = self::$container->get(Security::class);

        $loggedIn = $security->getUser();

        $this->assertNull($loggedIn);
    }
}
