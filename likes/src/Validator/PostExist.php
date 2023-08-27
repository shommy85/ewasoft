<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PostExist extends Constraint
{
    public string $message = 'Post with id \'{{ id }}\' does not exist.';
}