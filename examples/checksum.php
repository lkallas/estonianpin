<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Lkallas\Estonianpin\EstonianPIN;

$estonianPIN = new EstonianPIN();
$pin = '380071514320';

// Getting calculated checksum of the PIN. This function uses stage I and stage II(if needed) calculations. 
$checkSum = $estonianPIN->calculateCheckSum($pin);
printf('%s checksum is %d%s', $pin, $checkSum, PHP_EOL);

// Getting stage I calculated checksum. If this returns 10, stage II calculation needs to follow. Otherwise this will be the checksum.
$checkSumStageI = $estonianPIN->calculateCheckSumStageI($pin);
printf('%s stage I calculated checksum is %d%s', $pin, $checkSumStageI, PHP_EOL);

// Getting stage II calculated checksum. Is only calculated when stage I returns 10.
$checkSumStageII = $estonianPIN->calculateCheckSumStageII($pin);
printf('%s stage II calculated checksum is %d%s', $pin, $checkSumStageII, PHP_EOL);
