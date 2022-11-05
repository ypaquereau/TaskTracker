<?php

declare(strict_types=1);

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class UniqueUser extends Constraint
{
    public string $message = 'Email "{{ value }}" is already exists';
}
