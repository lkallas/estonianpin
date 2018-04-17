<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Lkallas\Estonianpin\EstonianPIN;

$estonianPIN = new EstonianPIN();
$pin = '49901150062';

// Simple format validation using regular expression.
$regexPassed = $estonianPIN->isValidatedByRegex($pin);
echo 'Validation using regular expression ' . ($regexPassed ? 'PASSED' : 'FAILED') . PHP_EOL;

// Advanced validation (calculates and validates also the control number and birth date).
$validated = $estonianPIN->validate($pin);
echo 'Validation with control number check ' . ($validated ? 'PASSSED' : 'FAILED') . PHP_EOL;

// Validation that throws corresponding exceptions on validation failures.
try {
    $estonianPIN->validateWithExceptions($pin);
    echo $pin . ' is a valid Estonian Personal Identification Number' . PHP_EOL;
} catch (Exception $exc) {
    echo $exc->getMessage();
}


