<?php

namespace App\Service;

use App\Entity\PhoneBookRecord;
use App\Repository\UserRepository;

class UserService
{
    public function getSharedUserIds(PhoneBookRecord $phoneBookRecord): array
    {
        $sharedUserIds = [];

        foreach ($phoneBookRecord->getSharedUsers() as $user) {
            $sharedUserIds[] = $user->getId();
        }

        return $sharedUserIds;
    }
}