<?php

namespace Lkallas\Estonianpin\Utils;

use PHPUnit\Framework\TestCase;
use Lkallas\Estonianpin\EstonianPIN;

class UtilsTest extends TestCase {

    /**
     * @var Utils
     */
    protected $utils;

    protected function setUp(): void {
        $this->utils = new Utils();
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::isUnderAge
     */
    public function testIsUnderAge() {
        $this->assertTrue($this->utils->isUnderAge('50301150038'));
        $this->assertTrue($this->utils->isUnderAge('60409170041'));
        $this->assertFalse($this->utils->isUnderAge('33009190013'));
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::isUnderAge
     */
    public function testIsUnderAgeWithCustomAgeLimit() {
        $this->assertTrue($this->utils->isUnderAge('50910150022', 21));
        $this->assertTrue($this->utils->isUnderAge('60003140055', 24));
        $this->assertFalse($this->utils->isUnderAge('38501120081', 30));
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::isUnderAge
     */
    public function testIsUnderAgeExceptionThrowing() {
        $this->expectException(\Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException::class);
        $this->utils->isUnderAge('80301450078');
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::isPensioner
     */
    public function testIsPensioner() {
        $this->assertTrue($this->utils->isPensioner('34503180113'));
        $this->assertTrue($this->utils->isPensioner('45012150112'));
        $this->assertFalse($this->utils->isPensioner('38501120081'));
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::isPensioner
     */
    public function testIsPensionerWithCustomAgeLimit() {
        $this->assertTrue($this->utils->isPensioner('35503230119', 60));
        $this->assertTrue($this->utils->isPensioner('34408250033', 55));
        $this->assertFalse($this->utils->isPensioner('39912120082', 40));
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::isPensioner
     */
    public function testIsPensionerExceptionThrowing() {
        $this->expectException(\Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException::class);
        $this->utils->isPensioner('1234567890');
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generateValidRandomDate
     */
    public function testGenerateValidRandomDate() {
        $date = $this->utils->generateValidRandomDate();
        $exploded = explode('.', $date);
        $this->assertTrue(checkdate(intval($exploded[1]), intval($exploded[0]), intval($exploded[2])));
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generateRandomDateArray
     */
    public function testGenerateRandomDateArray() {
        for ($i = 0; $i < 10; $i++) {
            $dateArr = $this->utils->generateRandomDateArray();

            $this->assertArrayHasKey('day', $dateArr);
            $this->assertArrayHasKey('month', $dateArr);
            $this->assertArrayHasKey('year', $dateArr);

            foreach ($dateArr as $key => $value) {
                $this->assertTrue(is_int($dateArr[$key]));
            }

            $this->assertTrue(
                    checkdate(intval($dateArr['month']), intval($dateArr['day']), intval($dateArr['year']))
            );
        }
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generateRandomMalePIN
     */
    public function testGenerateRandomMalePIN() {
        for ($i = 0; $i < 100; $i++) {
            $pin = $this->utils->generateRandomMalePIN();
            $this->assertNotEmpty($pin);
            $this->assertNotNull($pin);
            $this->assertTrue(intval(substr($pin, 0, 1)) % 2 !== 0);
            $this->assertMatchesRegularExpression(EstonianPIN::PIN_REGEX, $pin);
        }
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generateRandomFemalePIN
     */
    public function testGenerateRandomFemalePIN() {
        for ($i = 0; $i < 100; $i++) {
            $pin = $this->utils->generateRandomFemalePIN();
            $this->assertNotEmpty($pin);
            $this->assertNotNull($pin);
            $this->assertTrue(intval(substr($pin, 0, 1)) % 2 === 0);
            $this->assertMatchesRegularExpression(EstonianPIN::PIN_REGEX, $pin);
        }
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generateRandomPIN
     */
    public function testGenerateRandomPIN() {
        for ($i = 0; $i < 100; $i++) {
            $pin = $this->utils->generateRandomPIN();
            $this->assertNotEmpty($pin);
            $this->assertNotNull($pin);
            $this->assertMatchesRegularExpression(EstonianPIN::PIN_REGEX, $pin);
        }
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generate
     */
    public function testGenerate() {
        for ($i = 0; $i < 100; $i++) {
            $details = [
                'month' => random_int(1, 12),
                'day' => random_int(1, 28),
                'year' => random_int(intval(date('Y')) - 100, intval(date('Y'))),
                'gender' => ((random_int(1, 2) % 2 === 0) ? 'female' : 'male')
            ];
            $pin = $this->utils->generate($details);
            $this->assertNotEmpty($pin);
            $this->assertNotNull($pin);
            $this->assertMatchesRegularExpression(EstonianPIN::PIN_REGEX, $pin);
        }
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generate
     */
    public function testGenerateWithDetailsAsObject() {
        for ($i = 0; $i < 100; $i++) {
            $details = new \stdClass();
            $details->month = random_int(1, 12);
            $details->day = random_int(1, 28);
            $details->year = random_int(intval(date('Y')) - 100, intval(date('Y')));
            $details->gender = ((random_int(1, 2) % 2 === 0) ? 'female' : 'male');

            $pin = $this->utils->generate($details);
            $this->assertNotEmpty($pin);
            $this->assertNotNull($pin);
            $this->assertMatchesRegularExpression(EstonianPIN::PIN_REGEX, $pin);
        }
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generate
     */
    public function testGenerateWithExceptionThrowingOnEmptyDetails() {
        $this->expectException(\InvalidArgumentException::class);
        $this->utils->generate(null);
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generate
     */
    public function testGenerateWithExceptionThrowingOnInvalidGender() {
        $this->expectException(\InvalidArgumentException::class);
        $details = new \stdClass();
        $details->month = random_int(1, 12);
        $details->day = random_int(1, 28);
        $details->year = random_int(intval(date('Y')) - 100, intval(date('Y')));
        $details->gender = 'transsexual';
        $this->utils->generate($details);
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generate
     */
    public function testGenerateWithExceptionThrowingOnInvalidDate() {
        $this->expectException(\Lkallas\Estonianpin\Exceptions\InvalidDateException::class);
        $details = new \stdClass();
        $details->month = 2;
        $details->day = 30;
        $details->year = random_int(intval(date('Y')) - 100, intval(date('Y')));
        $details->gender = 'male';
        $this->utils->generate($details);
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::generate
     */
    public function testGenerateWithExceptionThrowingOnMissingMandatoryDetailKey() {
        $this->expectException(\InvalidArgumentException::class);
        $details = new \stdClass();
        $details->month = random_int(1, 12);
        $details->day = random_int(1, 28);
        $details->year = random_int(intval(date('Y')) - 100, intval(date('Y')));
        $this->utils->generate($details);
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::getPersonDetailsByPINAsArray
     */
    public function testGetPersonDetailsByPINAsArray() {
        $arr = $this->utils->getPersonDetailsByPINAsArray('38610150223');
        $this->assertArrayHasKey('gender', $arr);
        $this->assertArrayHasKey('serial', $arr);
        $this->assertArrayHasKey('day', $arr);
        $this->assertArrayHasKey('month', $arr);
        $this->assertArrayHasKey('year', $arr);
        $this->assertEquals(1986, $arr['year']);
        $this->assertEquals('male', $arr['gender']);
        $this->assertEquals(10, $arr['month']);
        $this->assertEquals(15, $arr['day']);
        $this->assertEquals('022', $arr['serial']);
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::getPersonDetailsByPIN
     */
    public function testGetPersonDetailsByPIN() {
        $details = $this->utils->getPersonDetailsByPIN('38610150223');
        $this->assertEquals(1986, $details->year);
        $this->assertEquals('male', $details->gender);
        $this->assertEquals(10, $details->month);
        $this->assertEquals(15, $details->day);
        $this->assertEquals('022', $details->serial);
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::getGenderAndCenturyIdentificationNumber
     */
    public function testGetGenderAndCenturyIdentificationNumber() {
        $this->assertEquals(6, $this->utils->getGenderAndCenturyIdentificationNumber(2012, EstonianPIN::GENDER_FEMALE));
        $this->assertEquals(3, $this->utils->getGenderAndCenturyIdentificationNumber(1986, EstonianPIN::GENDER_MALE));
        $this->assertNotEquals(5, $this->utils->getGenderAndCenturyIdentificationNumber(2017, EstonianPIN::GENDER_FEMALE));
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::getGenderAndCenturyIdentificationNumber
     */
    public function testGetGenderAndCenturyIdentificationNumberExceptionThrowingOnInvalidYear() {
        $this->expectException(\InvalidArgumentException::class);
        $this->utils->getGenderAndCenturyIdentificationNumber(1759, EstonianPIN::GENDER_FEMALE);
    }

    /**
     * @covers Lkallas\Estonianpin\Utils\Utils::getGenderAndCenturyIdentificationNumber
     */
    public function testGetGenderAndCenturyIdentificationNumberExceptionThrowingOnInvalidGender() {
        $this->expectException(\InvalidArgumentException::class);
        $this->utils->getGenderAndCenturyIdentificationNumber(2014, 'hermaphrodite');
    }

}
