<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class ContainsUrlVerify extends Constraint
{
    public $message = 'The url "{{ string }}" does not exist.';
}