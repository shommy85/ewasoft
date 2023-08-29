<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ThreadMessageValid extends Constraint
{
    public string $message = 'Message thread must be part of the same sender and receiver.';
}