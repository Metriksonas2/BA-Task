<?php

namespace App\Manager;

use App\Entity\PhoneBookRecord;
use App\Entity\User;
use App\Repository\PhoneBookRecordRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\This;

class PhoneBookRecordManager
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    public function addSharedUsersToRecord(PhoneBookRecord $phoneBookRecord, array $sharedUserIds): void
    {
        foreach ($sharedUserIds as $userId) {
            $sharedUser = $this->userRepository->find($userId);
            $phoneBookRecord->addSharedUser($sharedUser);
        }
    }

    public function unshareUser(PhoneBookRecord $phoneBookRecord, int $userId): void
    {
        $user = $this->userRepository->find($userId);
        $phoneBookRecord->removeSharedUser($user);
    }

    public function getSharedUserIds(PhoneBookRecord $phoneBookRecord): array
    {
        $sharedUserIds = [];

        foreach ($phoneBookRecord->getSharedUsers() as $user) {
            $sharedUserIds[] = $user->getId();
        }

        return $sharedUserIds;
    }
}