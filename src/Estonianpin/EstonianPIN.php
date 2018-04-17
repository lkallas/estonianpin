<?php

namespace Lkallas\Estonianpin;

use Lkallas\Estonianpin\Exceptions\InvalidDateException;
use Lkallas\Estonianpin\Exceptions\InvalidCheckSumException;
use Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException;

/**
 * Class for processing (parsing, validating etc) Estonian Personal Identification Numbers.
 *
 * @author Lennar Kallas <lennar@lennar.eu>
 */
class EstonianPIN {

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const PIN_REGEX = '/^[1-6](0\d{1}|[1-9]\d{1})(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{4}$/';

    /**
     * Get the person gender by Personal Identification Number. 
     * 
     * @param string $pin Estonian Personal Identification Number.
     * @return string Person gender: 'male' or 'female'.
     * @throws \Lkallas\Exceptions\InvalidPersonalIdentificationNrException 
     * If validation of Personal Identification Number fails.
     */
    public function getGender($pin): string {
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }

        $genderIdentifier = (int) substr($pin, 0, 1);
        return ($genderIdentifier % 2 === 0) ? self::GENDER_FEMALE : self::GENDER_MALE;
    }

    /**
     * Get the century of the persons birth date by Personal Identification Number.
     * 
     * @param string $pin Estonian Personal Identification Number.
     * @return int The century when the person was born eg. 1900, 2000 etc
     * @throws \Lkallas\Exceptions\InvalidPersonalIdentificationNrException 
     * If validation of Personal Identification Number fails.
     */
    public function getBirthCentury($pin): int {
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }

        $century = 0;
        $centuryIdentificator = (int) substr($pin, 0, 1);

        if ($centuryIdentificator < 3) {
            $century = 1800;
        } elseif ($centuryIdentificator > 2 && $centuryIdentificator < 5) {
            $century = 1900;
        } elseif ($centuryIdentificator > 4 && $centuryIdentificator < 7) {
            $century = 2000;
        }
        return $century;
    }

    /**
     * Get the person's year of birth by Personal Identification Number.
     * 
     * @param string $pin Estonian Personal Identification Number.
     * @return int The year when the person was born.
     * @throws InvalidPersonalIdentificationNrException 
     * If validation of Personal Identification Number fails.
     */
    public function getYearOfBirth($pin): int {
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }
        $year = (int) ltrim(substr($pin, 1, 2), '0');
        $century = $this->getBirthCentury($pin);
        return $year + $century;
    }

    /**
     * Get the person's month of birth by Personal Identification Number.
     * 
     * @param type $pin Estonian Personal Identification Number.
     * @return int The month when the person was born.
     * @throws InvalidPersonalIdentificationNrException
     * If validation of Personal Identification Number fails.
     */
    public function getMonthOfBirth($pin): int {
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }
        return (int) ltrim(substr($pin, 3, 2), '0');
    }

    /**
     * Get the person's day of birth by Estonian Personal Identification Number.
     * 
     * @param type $pin Estonian Personal Identification Number.
     * @return int The day when the person was born.
     * @throws InvalidPersonalIdentificationNrException
     * If validation of Personal Identification Number fails.
     */
    public function getDayOfBirth($pin): int {
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }
        return (int) ltrim(substr($pin, 5, 2), '0');
    }

    /**
     * Get the person's serial number from Estonian Personal Identification Number.
     * 
     * Prior to year of 2013 serial number consisted a hospital identification 
     * number and the birth number in the hospital of that given day.
     * 
     * Starting 2013 all newborns get their serial number from common Estonian register 
     * and the number is a birth number of that given day in Estonia. 
     * 
     * @param string $pin Estonian Personal Identification Number.
     * @return string Serial number which can consist leading zeros.
     * @throws InvalidPersonalIdentificationNrException
     * If validation of Personal Identification Number fails.
     */
    public function getSerialNumber($pin): string {
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }
        return substr($pin, 7, 3);
    }

    /**
     * Get the person's date of birth as PHP DateTime object by Personal Identification Number.
     * 
     * @param type $pin Estonian Personal Identification Number.
     * @return \DateTime The date of birth as PHP DateTime object.
     * @throws InvalidPersonalIdentificationNrException
     * If validation of Personal Identification Number fails.
     */
    public function getBirthDateAsDatetimeObj($pin): \DateTime {
        if (!$this->validate($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }

        $dateOfBirth = sprintf(
                '%d.%d.%d', $this->getMonthOfBirth($pin), $this->getDayOfBirth($pin), $this->getYearOfBirth($pin)
        );

        return \Datetime::createFromFormat('m.d.Y', $dateOfBirth);
    }

    /**
     * Get the person's current age as PHP DateInterval object.
     * Note that DateInterval calculation precision depends on timezone set in php.ini.
     * 
     * @param type $pin Estonian Personal Identification Number.
     * @return \DateInterval The current age as PHP DateInterval object.
     * @throws InvalidPersonalIdentificationNrException
     * If validation of Personal Identification Number fails.
     */
    public function getCurrentAgeByPIN($pin): \DateInterval {
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }

        $birthDay = $this->getBirthDateAsDatetimeObj($pin);
        $now = new \Datetime('now');
        return $now->diff($birthDay);
    }

    public function getCurrentAgeInYearsByPIN($pin): int {
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Number format!');
        }

        return $this->getCurrentAgeByPIN($pin)->y;
    }

    /**
     * Performs regular expression check against Estonian Personal Identification Number.
     * Note that this does not perform full validation on dates(leap years), control number etc.
     * Use this only to pre-validate the format of the Personal Identification Number.
     * 
     * 
     * @param type $pin Estonian Personal Identification Number.
     * @return bool True if Estonian Personal Identification Number matches the 
     * regular expression, false otherwise.
     */
    public function isValidatedByRegex($pin): bool {
        return is_string($pin) && preg_match(self::PIN_REGEX, $pin);
    }

    /**
     * Validate Estonian Personal Identification Number against all rules set 
     * by Estonian laws.
     * 
     * @param string $pin Estonian Personal Identification Number.
     * @return bool True if the Personal Identification Number passes all 
     * validation steps, false otherwise.
     */
    public function validate($pin): bool {
        try {
            return $this->validateWithExceptions($pin);
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * Validates Estonian Personal Identification Number. 
     * Throws descriptive exceptions if any of the validation steps fails.
     * 
     * @param type $pin Estonian Personal Identification Number.
     * @return bool True if Estonian Personal Identification Number passes 
     * all validations, false otherwise.
     * @throws InvalidDateException
     * @throws InvalidCheckSumException
     * @throws InvalidPersonalIdentificationNrException
     */
    public function validateWithExceptions($pin): bool {
        // Initial check: must be string and match the regex.
        if (!$this->isValidatedByRegex($pin)) {
            throw new InvalidPersonalIdentificationNrException($pin . 'is not a valid Personal Identification Number. '
            . 'Check length, gender/century indentificator and date format.'
            );
        }

        // Check if the birth date is a valid date. Leap years are taken into consideration.
        if (!checkdate($this->getMonthOfBirth($pin), $this->getDayOfBirth($pin), $this->getYearOfBirth($pin))) {
            throw new InvalidDateException($pin . ' has invalid birthdate!');
        }

        // Checksum of the personal identification number (last digit)
        $checkSum = (int) substr($pin, -1);
        $calculatedCheckSum = $this->calculateCheckSum($pin);

        if ($checkSum !== $calculatedCheckSum) {
            throw new InvalidCheckSumException(
            sprintf('%s has invalid control number. Is %d but should be %d!', $pin, $checkSum, $calculatedCheckSum)
            );
        }

        return true;
    }

    /**
     * Calculates the Estonian Personal Identification Number checksum.
     * 
     * @param string $pin Estonian Personal Identification Number.
     * @return int Calculated checksum.
     */
    public function calculateCheckSum($pin): int {
        $checkSum = $this->calculateCheckSumStageI($pin);

        // Calculate again if remainder is 10
        if ($checkSum === 10) {
            $checkSum = $this->calculateCheckSumStageII($pin);
        }

        return $checkSum;
    }

    /**
     * Calculate Personal Identification Number stage I checksum.
     * 
     * In this stage the first 10 digits from left to right are multiplied with 
     * corresponding number from sequence (1,2,3,4,5,6,7,8,9,1). 
     * Resulting products are then added together.
     * Finally modulo 11 calculation is applied.
     * 
     * @param string $pin Estonian Personal Identification Number.
     * @return int Control number calculated using modulo 11 algorithm.
     * Using multiplier sequence (1,2,3,4,5,6,7,8,9,1).
     */
    public function calculateCheckSumStageI($pin): int {
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $multiplier = $i + 1;
            $sum += ((int) (substr($pin, $i, 1)) * ($multiplier > 9 ? 1 : $multiplier));
        }

        return $sum % 11;
    }

    /**
     * Calculate Personal Identification Number stage II checksum. 
     * This stage is only needed when the result from stage I equals 10.
     * 
     * In this stage the first 10 digits from left to right are multiplied with 
     * corresponding number from sequence (3,4,5,6,7,8,9,1,2,3). 
     * Resulting products are then added together.
     * Finally modulo 11 calculation is applied.
     * 
     * @param string $pin Estonian Personal Identification Number.
     * @return int Control number calculated using modulo 11 algorithm.
     * Using multiplier sequence (3,4,5,6,7,8,9,1,2,3). 
     * If modulo 11 calculation equals 10 the control sum is 0, remainder otherwise.
     */
    public function calculateCheckSumStageII($pin): int {
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $multiplier = $i + 3;
            $sum += ((int) (substr($pin, $i, 1)) * ($multiplier > 6 ? $i - 6 : $multiplier));
        }

        $remainder = $sum % 11;

        return ($remainder === 10) ? 0 : $remainder;
    }

}
