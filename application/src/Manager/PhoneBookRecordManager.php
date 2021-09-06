<?php

namespace App\Manager;

use App\Entity\PhoneBookRecord;
use App\Entity\User;
use App\Repository\PhoneBookRecordRepository;
use App\Repository\UserRepository;
use App\Service\RequestParametersValidator;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PhoneBookRecordManager
{
    private EntityManagerInterface $entityManager;
    private RequestParametersValidator $parametersValidator;

    public function __construct(EntityManagerInterface $entityManager, RequestParametersValidator $parametersValidator)
    {
        $this->entityManager = $entityManager;
        $this->parametersValidator = $parametersValidator;
    }

    public function editPhoneBookRecord(Request $request, PhoneBookRecord $phoneBookRecord)
    {
        $values = [
            'firstName' => trim($request->request->get('firstName')),
            'lastName' => trim($request->request->get('lastName')),
            'email' => trim($request->request->get('email')),
            'phoneNumber' => trim($request->request->get('phoneNumber')),
        ];

        /** @throws InvalidParameterException When invalid parameters are entered */
        $this->parametersValidator->validateUser($values);

        $phoneBookRecord->setFirstName($request->request->get('firstName'));
        $phoneBookRecord->setLastName($request->request->get('lastName'));
        $phoneBookRecord->setEmail($request->request->get('email'));
        $phoneBookRecord->setPhoneNumber($request->request->get('phoneNumber'));

        $this->entityManager->flush();
    }

    public function createPhoneBookRecord(PhoneBookRecord $newPhoneBookRecord, User $user)
    {
        $newPhoneBookRecord->setCreator($user);

        $this->entityManager->persist($newPhoneBookRecord);
        $this->entityManager->flush();
    }

    public function deleteRecord(PhoneBookRecord $phoneBookRecord)
    {
        $this->entityManager->remove($phoneBookRecord);
        $this->entityManager->flush();
    }
}