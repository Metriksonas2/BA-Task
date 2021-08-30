<?php

namespace App\Tests\Feature;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneBookRecordControllerTest extends WebTestCase
{
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;
    private UserRepository|null $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    private function login(): void
    {
        $testUser = $this->userRepository->findOneByEmail('eriksonaslol@gmail.com');
        $this->client->loginUser($testUser);
    }

    /** @test */
    public function redirectWhenNotAuthorized(): void
    {
        $this->client->request('GET', '/records');
        $this->assertResponseRedirects('/login', 302);

        $this->client->request('GET', '/shared');
        $this->assertResponseRedirects('/login', 302);

        $this->client->request('GET', '/users');
        $this->assertResponseRedirects('/login', 302);
    }

    /** @test */
    public function myRecordsPage(): void
    {
        $this->login();
        $this->client->request('GET', '/records');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'My personal records');
    }

    /** @test */
    public function sharedRecordsPage(): void
    {
        $this->login();
        $this->client->request('GET', '/shared');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Shared records');
    }
}
