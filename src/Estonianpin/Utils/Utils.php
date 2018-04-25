<?php

namespace Lkallas\Estonianpin\Utils;

use Lkallas\Estonianpin\EstonianPIN;
use Lkallas\Estonianpin\Exceptions\InvalidDateException;
use Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException;

/**
 * Utility functions for Estonian Personal Identification Code.
 *
 * @author Lennar Kallas <lennar@lennar.eu>
 */
class Utils {

    const GENERATOR_MANDATORY_KEYS = ['gender', 'year', 'month', 'day'];

    /**
     *
     * @var EstonianPIN
     */
    private $estonianPIN;

    /**
     * @codeCoverageIgnore
     */
    public function __construct() {
        $this->estonianPIN = new EstonianPIN();
    }

    /**
     * Determine if the person is underaged.
     * 
     * @param string $pin Estonian Personal Identification Code.
     * @param int $ageLimit Age in years from which person is not considered underaged anymore. 
     * In Estonia this age is 18. In some institutions person under age of 21 is 
     * considered underaged as well. Default value is 18.
     * 
     * @return bool True if the person is underaged (below the set age limit), false otherwise.
     * @throws InvalidPersonalIdentificationNrException
     * If validation of Personal Identification Code fails.
     */
    public function isUnderAge($pin, $ageLimit = 18): bool {
        if (!$this->estonianPIN->validate($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Code!');
        }

        return ($this->estonianPIN->getCurrentAgeInYearsByPIN($pin) < $ageLimit);
    }

    /**
     * Determine if the person is a pensioner by the pension law in Estonia.
     * 
     * @param string $pin Estonian Personal Identification Code.
     * @param int $pensionAge Age in years from which a person is considered as 
     * a pensioner by law and eligible for receiving national pension in Estonia. 
     * Default value is 65, but may differ - depending on the person's year of birth.
     * 
     * @return bool True if the person is considered as a pensioner by Estonian laws, false otherwise.
     * @throws InvalidPersonalIdentificationNrException
     * If validation of Personal Identification Code fails.
     */
    public function isPensioner($pin, $pensionAge = 65): bool {
        if (!$this->estonianPIN->validate($pin)) {
            throw new InvalidPersonalIdentificationNrException('Invalid Personal Identification Code!');
        }

        return ($this->estonianPIN->getCurrentAgeInYearsByPIN($pin) >= $pensionAge);
    }

    /**
     * Gets the details about Estonian citizen by his/her Personal Identification Code.
     * 
     * @param string $pin Estonian Personal Identification Code.
     * @return array Associative array with 
     * keys 'gender' (male/female), 'year', 'month', 'day', 'serial'.
     */
    public function getPersonDetailsByPINAsArray($pin): array {
        return [
            'gender' => $this->estonianPIN->getGender($pin),
            'year' => $this->estonianPIN->getYearOfBirth($pin),
            'month' => $this->estonianPIN->getMonthOfBirth($pin),
            'day' => $this->estonianPIN->getDayOfBirth($pin),
            'serial' => $this->estonianPIN->getSerialNumber($pin)
        ];
    }

    /**
     * Get the gender and century identification number for Estonian Personal 
     * Identification Number.
     * 
     * @param int $year Year of birth.
     * @param string $gender 'male' or 'female'
     * @return int Gender and century identification number.
     * @throws \InvalidArgumentException
     * If year or gender does not match the expected criteria.
     */
    public function getGenderAndCenturyIdentificationNumber($year, $gender): int {

        if (!is_int($year) || $year < 1800 || $year > 2100) {
            throw new \InvalidArgumentException('Year must be an integer between 1800 and 2100!');
        }

        if ($gender !== EstonianPIN::GENDER_FEMALE && $gender !== EstonianPIN::GENDER_MALE) {
            throw new \InvalidArgumentException('Gender must be either: '
            . EstonianPIN::GENDER_FEMALE . ' or ' . EstonianPIN::GENDER_MALE
            );
        }

        $century = (int) floor($year / 100) * 100;

        $identificator = 1;
        for ($i = 1800; $i <= 2100; $i += 100) {
            if ($i === $century) {
                $identificator += (EstonianPIN::GENDER_FEMALE === $gender ? 1 : 0);
                break;
            }
            $identificator += 2;
        }
        return $identificator;
    }

    /**
     * Gets the details about Estonian citizen by his/her Personal Identification Code.
     * 
     * @param string $pin Estonian Personal Identification Code.
     * @return \stdClass Object with properties gender (male/female), year, month, day, serial.
     */
    public function getPersonDetailsByPIN($pin): \stdClass {
        return (object) $this->getPersonDetailsByPINAsArray($pin);
    }

    /**
     * Generates random & valid Estonian Personal Identification Code for male person.
     * 
     * @return string Estonian Personal Identification Code.
     */
    public function generateRandomMalePIN(): string {
        $arr = $this->generateRandomDateArray();
        $arr['gender'] = EstonianPIN::GENDER_MALE;
        return $this->generate($arr);
    }

    /**
     * Generates random & valid Estonian Personal Identification Code for female person.
     * 
     * @return string Estonian Personal Identification Code.
     */
    public function generateRandomFemalePIN(): string {
        $arr = $this->generateRandomDateArray();
        $arr['gender'] = EstonianPIN::GENDER_FEMALE;
        return $this->generate($arr);
    }

    /**
     * Generates random & valid Estonian Personal Identification Code.
     * 
     * @return string Estonian Personal Identification Code.
     */
    public function generateRandomPIN(): string {
        $arr = $this->generateRandomDateArray();
        $arr['gender'] = (random_int(1, 8) % 2 === 0) ? EstonianPIN::GENDER_FEMALE : EstonianPIN::GENDER_MALE;
        return $this->generate($arr);
    }

    /**
     * Generates Estonian Personal Identification Code using the given person details.
     * 
     * @param array|object $details Associative array or object with person details.
     * @throws \InvalidArgumentException
     * If person details does not contain mandatory values.
     */
    public function generate($details): string {

        if (is_object($details)) {
            $details = (array) $details;
        }

        if (empty($details) || !is_array($details)) {
            throw new \InvalidArgumentException('Invalid parameter! '
            . 'The parameter must be an associative array with person details.'
            );
        }

        foreach (self::GENERATOR_MANDATORY_KEYS as $key) {
            if (!array_key_exists($key, $details) || empty($details[$key])) {
                throw new \InvalidArgumentException(
                'Mandatory key "' . $key . '" is missing or empty!'
                );
            }
        }

        $gender = $details['gender'];
        if ($gender !== EstonianPIN::GENDER_FEMALE && $gender !== EstonianPIN::GENDER_MALE) {
            throw new \InvalidArgumentException('Gender must be either: '
            . EstonianPIN::GENDER_FEMALE . ' or ' . EstonianPIN::GENDER_MALE
            );
        }

        if (!checkdate($details['month'], $details['day'], $details['year'])) {
            throw new InvalidDateException('The provided date is not a valid date!');
        }

        $genderAndCentury = $this->getGenderAndCenturyIdentificationNumber($details['year'], $gender);
        $centuryYear = substr($details['year'], -2);
        $serialNo = sprintf('%03d', strval(random_int(1, 999)));

        $base = $genderAndCentury
                . $centuryYear
                . sprintf('%02d', strval($details['month']))
                . sprintf('%02d', strval($details['day']))
                . $serialNo;

        $checkSum = $this->estonianPIN->calculateCheckSum($base);

        return $base . $checkSum;
    }

    /**
     * Generates valid random date up to 100 years back of the current date.
     * 
     * @return string Valid date in 'j.n.Y' format.
     */
    public function generateValidRandomDate(): string {
        return date('d.m.Y', random_int(strtotime('-100 year'), strtotime('now')));
    }

    /**
     * Generates valid random date as associative array with keys 'year', 'month', 'day'.
     * 
     * @return array Random date as associative array with keys 'year', 'month', 'day'.
     */
    public function generateRandomDateArray(): array {
        $time = explode('.', $this->generateValidRandomDate());
        return [
            'year' => (int) $time[2],
            'month' => (int) $time[1],
            'day' => (int) $time[0]
        ];
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @return Iterable
     */
    public function getPinsGeneratorForRange(\DateTime $start, \DateTime $end): iterable
    {
        $currentDate = $start;
        while ($currentDate <= $end) {
            for ($i = 1; $i < 1000; $i++) {
                $year = (int)$currentDate->format('Y');
                $genderAndCenturyMale = $this->getGenderAndCenturyIdentificationNumber($year, EstonianPIN::GENDER_MALE);
                $genderAndCenturyFemale = $this->getGenderAndCenturyIdentificationNumber($year, EstonianPIN::GENDER_FEMALE);
                $centuryYear = substr((string)$year, -2);
                $serialNo = sprintf('%03d', $i);

                $postFix = $centuryYear
                    . sprintf('%02d', strval((int)$currentDate->format('m')))
                    . sprintf('%02d', strval((int)$currentDate->format('d')))
                    . $serialNo;
                $baseMale = $genderAndCenturyMale . $postFix;
                $baseFemale = $genderAndCenturyFemale . $postFix;

                $checkSumMale = $this->estonianPIN->calculateCheckSum($baseMale);
                $checkSumFemale = $this->estonianPIN->calculateCheckSum($baseFemale);

                yield $baseFemale . $checkSumFemale;
                yield $baseMale . $checkSumMale;
            }
            $currentDate->add(new \DateInterval("P1D"));
        }
    }

}
