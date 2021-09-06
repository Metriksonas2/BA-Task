<?php

namespace App\Service;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class RequestParametersValidator
{

    public function validateUser(array $parameters)
    {
        $constraints = new Collection([
            'firstName' => [
                new NotBlank()
            ],
            'lastName' => [
                new NotBlank()
            ],
            'email' => [
                new NotBlank(),
                new Email()
            ],
            'phoneNumber' => [
                new NotBlank()
            ]
        ]);

        $validator = Validation::createValidator();
        $violations = $validator->validate($parameters, $constraints);

        if($violations->count() !== 0) {
            throw new InvalidParameterException($violations[0]->getMessage());
        }
    }
}