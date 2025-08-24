<?php

// src/Validator/Constraints/ContainsSpecialCharacters.php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsSpecialCharacters extends Constraint
{
    public $message = 'The string "{{ string }}" contains special characters.';
}

