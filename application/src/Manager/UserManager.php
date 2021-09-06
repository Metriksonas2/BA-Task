<?php

namespace App\Manager;

use App\Entity\PhoneBookRecord;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function addSharedUsersToRecord(PhoneBookRecord $phoneBookRecord, array $sharedUserIds): void
    {
        foreach ($sharedUserIds as $userId) {
            $sharedUser = $this->userRepository->find($userId);
            $phoneBookRecord->addSharedUser($sharedUser);
        }

        $this->entityManager->flush();
    }

    public function unshareUser(PhoneBookRecord $phoneBookRecord, int $userId): void
    {
        $user = $this->userRepository->find($userId);
        $phoneBookRecord->removeSharedUser($user);

        $this->entityManager->flush();
    }
}