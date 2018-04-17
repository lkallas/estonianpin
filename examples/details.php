<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Lkallas\Estonianpin\EstonianPIN;

$estonianPIN = new EstonianPIN();
$pin = '38007150432';

// Getting the gender. 
$gender = $estonianPIN->getGender($pin);
printf('According to %s the person\'s gender is %s%s', $pin, $gender, PHP_EOL);

// Getting the person's birth century (1800, 1900, 2000 etc)
$century = $estonianPIN->getBirthCentury($pin);
printf('According to %s the person\'s birth century is %d%s', $pin, $century, PHP_EOL);

// Getting the person's birth date as DateTime object.
$birthDateObj = $estonianPIN->getBirthDateAsDatetimeObj($pin);
printf('According to %s the person\'s birth date is %s%s', $pin, $birthDateObj->format('d.m.Y'), PHP_EOL);

// Getting the person's day of birth.
$dayOfBirth = $estonianPIN->getDayOfBirth($pin);
printf('According to %s the person\'s day of birth is %d%s', $pin, $dayOfBirth, PHP_EOL);

// Getting the person's month of birth.
$monthOfBirth = $estonianPIN->getMonthOfBirth($pin);
printf('According to %s the person\'s month of birth is %d%s', $pin, $monthOfBirth, PHP_EOL);

// Getting the person's year of birth.
$yearOfBirth = $estonianPIN->getYearOfBirth($pin);
printf('According to %s the person\'s year of birth is %d%s', $pin, $yearOfBirth, PHP_EOL);

// Getting the person's serial number.
$serialNo = $estonianPIN->getSerialNumber($pin);
printf('According to %s the person\'s serial number is %s%s', $pin, $serialNo, PHP_EOL);

// Getting the person's current age in years.
$ageInYears = $estonianPIN->getCurrentAgeInYearsByPIN($pin);
printf('According to %s the person is currently %d years old%s', $pin, $ageInYears, PHP_EOL);

// Getting the person's current age in years, months and days.
$age = $estonianPIN->getCurrentAgeByPIN($pin);
printf('According to %s the person is currently %d years, %d months and %d days old%s', $pin, $age->y, $age->m, $age->d, PHP_EOL);
