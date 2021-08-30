<?php

namespace App\Tests\Unit;

use App\Entity\PhoneBookRecord;
use App\Entity\User;
use App\Repository\PhoneBookRecordRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PhoneBookRecordManagerTest extends TestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
    }

    /** @test */
    public function successfullyCreatesPhoneBookRecord(): void
    {
        $phoneBookRecord = new PhoneBookRecord();
        $phoneNumber = '222-111-1897';
        $phoneBookRecord->setPhoneNumber($phoneNumber);
        $creator = $this->createMock(User::class);
        $phoneBookRecordRepository = $this->createMock(PhoneBookRecordRepository::class);

        $this->entityManager->method('getRepository')->with(PhoneBookRecordRepository::class)
            ->willReturn($phoneBookRecordRepository);

        $phoneBookRecordRepository->method('findOneBy')->willReturn($phoneBookRecord);

        $resultPhoneBookRecord = $phoneBookRecordRepository->findOneBy(['phoneNumber' => $phoneNumber]);
        $this->assertSame($phoneBookRecord, $resultPhoneBookRecord);
    }
}
