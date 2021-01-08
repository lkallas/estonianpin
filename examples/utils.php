<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Lkallas\Estonianpin\Utils\Utils;

$utils = new Utils();

// Generates random but valid PIN
$pin = $utils->generateRandomPIN();
printf('Random PIN: %s%s', $pin, PHP_EOL);

// Check if a person is underage (age less than 18 years)
$isUnder18 = $utils->isUnderAge($pin);
printf('According to %s the person %s less than 18 years old%s', $pin, ($isUnder18 ? 'is' : 'is not'), PHP_EOL);

// Check if a person is underage (age less than 21 years)
$isUnder21 = $utils->isUnderAge($pin, 21);
printf('According to %s the person %s less than 21 years old%s', $pin, ($isUnder21 ? 'is' : 'is not'), PHP_EOL);

// Check if a person is a pensioner (age is at least 65)
$isPensioner = $utils->isPensioner($pin);
printf('According to %s the person %s a pensioner%s', $pin, ($isPensioner ? 'is' : 'is not'), PHP_EOL);

// Generate random male person PIN
$randomMale = $utils->generateRandomMalePIN();
printf('Random male person PIN: %s%s', $randomMale, PHP_EOL);

// Generate random female person PIN
$randomFemale = $utils->generateRandomFemalePIN();
printf('Random female person PIN: %s%s', $randomFemale, PHP_EOL);

// Generate random female person PIN
$random = $utils->generate(['gender' => 'male', 'year' => 1986, 'day' => 30, 'month' => 1]);
printf('Random PIN from provided details: %s%s', $random, PHP_EOL);

// Get details about the person
$details = json_encode($utils->getPersonDetailsByPINAsArray($pin), JSON_PRETTY_PRINT);
printf('According to %s the details about the person are: %s%s', $pin, $details, PHP_EOL);

$pinGenerator = $utils->getPinsGeneratorForRange(new \DateTime('1991-01-01'), new \DateTime('1991-01-02'));
foreach ($pinGenerator as $n => $pin) {
    printf('PIN number %d for 1991-01-01 is %s%s', $n + 1, $pin, PHP_EOL);
    if ($n > 4) {
        break;
    }
}
