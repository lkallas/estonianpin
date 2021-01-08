<?php

namespace Lkallas\Estonianpin;

use Lkallas\Estonianpin\EstonianPIN;
use PHPUnit\Framework\TestCase;

class EstonianPINTest extends TestCase {

    /**
     * @var EstonianPIN
     */
    protected $estonianPIN;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->estonianPIN = new EstonianPIN();
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getGender
     */
    public function testGetGender() {
        $this->assertEquals(EstonianPIN::GENDER_MALE, $this->estonianPIN->getGender('18610154321'));
        $this->assertEquals(EstonianPIN::GENDER_FEMALE, $this->estonianPIN->getGender('28610154321'));
        $this->assertEquals(EstonianPIN::GENDER_MALE, $this->estonianPIN->getGender('38610154321'));
        $this->assertEquals(EstonianPIN::GENDER_FEMALE, $this->estonianPIN->getGender('48610154321'));
        $this->assertEquals(EstonianPIN::GENDER_MALE, $this->estonianPIN->getGender('50110154321'));
        $this->assertEquals(EstonianPIN::GENDER_FEMALE, $this->estonianPIN->getGender('60110154321'));
        $this->assertNotEquals(EstonianPIN::GENDER_MALE, $this->estonianPIN->getGender('60110154321'));
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getGender
     */
    public function testGetGenderExceptionThrowing() {
        $this->estonianPIN->getGender('30113454321');
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getBirthCentury
     */
    public function testGetBirthCentury() {
        $this->assertEquals(1800, $this->estonianPIN->getBirthCentury('18610154321'));
        $this->assertEquals(1800, $this->estonianPIN->getBirthCentury('28610154321'));
        $this->assertEquals(1900, $this->estonianPIN->getBirthCentury('38610154321'));
        $this->assertEquals(1900, $this->estonianPIN->getBirthCentury('48610154321'));
        $this->assertEquals(2000, $this->estonianPIN->getBirthCentury('50110154321'));
        $this->assertEquals(2000, $this->estonianPIN->getBirthCentury('60110154321'));
        $this->assertNotEquals(3000, $this->estonianPIN->getBirthCentury('60110154321'));
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getBirthCentury
     */
    public function testGetBirthCenturyExceptionThrowing() {
        $this->estonianPIN->getBirthCentury('30113454321');
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getYearOfBirth
     */
    public function testGetYearOfBirth() {
        $this->assertEquals(1886, $this->estonianPIN->getYearOfBirth('18610154321'));
        $this->assertEquals(1886, $this->estonianPIN->getYearOfBirth('28610154321'));
        $this->assertEquals(1986, $this->estonianPIN->getYearOfBirth('38610154321'));
        $this->assertEquals(1986, $this->estonianPIN->getYearOfBirth('48610154321'));
        $this->assertEquals(2001, $this->estonianPIN->getYearOfBirth('50110154321'));
        $this->assertEquals(2001, $this->estonianPIN->getYearOfBirth('60110154321'));
        $this->assertNotEquals(3001, $this->estonianPIN->getYearOfBirth('60110154321'));
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getYearOfBirth
     */
    public function testGetYearOfBirthExceptionThrowing() {
        $this->estonianPIN->getYearOfBirth('30113454321');
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getMonthOfBirth
     */
    public function testGetMonthOfBirth() {
        $this->assertEquals(11, $this->estonianPIN->getMonthOfBirth('18611154321'));
        $this->assertEquals(12, $this->estonianPIN->getMonthOfBirth('28612154321'));
        $this->assertEquals(10, $this->estonianPIN->getMonthOfBirth('38610154321'));
        $this->assertEquals(9, $this->estonianPIN->getMonthOfBirth('48609154321'));
        $this->assertEquals(8, $this->estonianPIN->getMonthOfBirth('50108154321'));
        $this->assertEquals(7, $this->estonianPIN->getMonthOfBirth('60107154321'));
        $this->assertNotEquals(13, $this->estonianPIN->getMonthOfBirth('60110154321'));
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getMonthOfBirth
     */
    public function testGetMonthOfBirthExceptionThrowing() {
        $this->estonianPIN->getMonthOfBirth('30113454321');
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getDayOfBirth
     */
    public function testGetDayOfBirth() {
        $this->assertEquals(15, $this->estonianPIN->getDayOfBirth('18611154321'));
        $this->assertEquals(14, $this->estonianPIN->getDayOfBirth('28612144321'));
        $this->assertEquals(13, $this->estonianPIN->getDayOfBirth('38610134321'));
        $this->assertEquals(12, $this->estonianPIN->getDayOfBirth('48609124321'));
        $this->assertNotEquals(1, $this->estonianPIN->getDayOfBirth('60110154321'));
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getDayOfBirth
     */
    public function testGetDayOfBirthExceptionThrowing() {
        $this->estonianPIN->getDayOfBirth('30113454321');
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getBirthDateAsDatetimeObj
     */
    public function testGetBirthDateAsDatetimeObj() {
        $dateTime1 = $this->estonianPIN->getBirthDateAsDatetimeObj('50301150038');
        $dateTime2 = $this->estonianPIN->getBirthDateAsDatetimeObj('39912120114');
        $dateTime3 = $this->estonianPIN->getBirthDateAsDatetimeObj('35603150076');

        $this->assertEquals('01.15.2003', $dateTime1->format('m.d.Y'));
        $this->assertEquals('12.12.1999', $dateTime2->format('m.d.Y'));
        $this->assertNotEquals('10.11.1945', $dateTime3->format('m.d.Y'));
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getBirthDateAsDatetimeObj
     */
    public function testGetBirthDateAsDatetimeObjExceptionThrowing() {
        $this->estonianPIN->getBirthDateAsDatetimeObj(null);
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getCurrentAgeByPIN
     */
    public function testGetCurrentAgeByPIN() {
        $this->assertInstanceOf(\DateInterval::class, $this->estonianPIN->getCurrentAgeByPIN('35506210055'));
        $this->assertNotNull($this->estonianPIN->getCurrentAgeByPIN('35506210055')->y);
        $this->assertNotNull($this->estonianPIN->getCurrentAgeByPIN('35506210055')->m);
        $this->assertNotNull($this->estonianPIN->getCurrentAgeByPIN('35506210055')->d);
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getCurrentAgeByPIN
     */
    public function testGetCurrentAgeByPINExceptionThrowing() {
        $this->estonianPIN->getCurrentAgeByPIN('');
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getCurrentAgeInYearsByPIN
     */
    public function testGetCurrentAgeInYearsByPIN() {
        $this->assertNotNull($this->estonianPIN->getCurrentAgeInYearsByPIN('35506218162'));
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getCurrentAgeInYearsByPIN
     */
    public function testGetCurrentAgeInYearsByPINExceptionThrowing() {
        $this->estonianPIN->getCurrentAgeInYearsByPIN('gummybear');
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::isValidatedByRegex
     */
    public function testIsValidatedByRegex() {
        $this->assertFalse($this->estonianPIN->isValidatedByRegex('85501120123'));
        $this->assertFalse($this->estonianPIN->isValidatedByRegex('48813013210'));
        $this->assertFalse($this->estonianPIN->isValidatedByRegex('50201447788'));
        $this->assertTrue($this->estonianPIN->isValidatedByRegex('61707262456'));
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::validate
     */
    public function testValidate() {
        $this->assertFalse($this->estonianPIN->validate('355062181624321'));
        $this->assertFalse($this->estonianPIN->validate('39902310167'));
        $this->assertFalse($this->estonianPIN->validate('39902230300'));
        $this->assertTrue($this->estonianPIN->validate('38610150180'));
        $this->assertFalse($this->estonianPIN->validate('39310075456'));
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::calculateCheckSum
     */
    public function testCalculateCheckSum() {
        $this->assertEquals(5, $this->estonianPIN->calculateCheckSum('38610150005'));
        $this->assertEquals(0, $this->estonianPIN->calculateCheckSum('38610150060'));
        $this->assertEquals(5, $this->estonianPIN->calculateCheckSum('48011220235'));
        $this->assertNotEquals(6, $this->estonianPIN->calculateCheckSum('39310075456'));
        $this->assertNotEquals(1, $this->estonianPIN->calculateCheckSum('38610150060'));
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::calculateCheckSumStageI
     */
    public function testCalculateCheckSumStageI() {
        $this->assertEquals(1, $this->estonianPIN->calculateCheckSumStageI('38610155611'));
        $this->assertEquals(10, $this->estonianPIN->calculateCheckSumStageI('39310075456'));
        $this->assertEquals(10, $this->estonianPIN->calculateCheckSumStageI('48011220235'));
        $this->assertNotEquals(5, $this->estonianPIN->calculateCheckSumStageI('38610155467'));
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::calculateCheckSumStageII
     */
    public function testCalculateCheckSumStageII() {
        $this->assertEquals(5, $this->estonianPIN->calculateCheckSumStageII('48011220235'));
        $this->assertNotEquals(0, $this->estonianPIN->calculateCheckSumStageII('38007151430'));
        $this->assertNotEquals(5, $this->estonianPIN->calculateCheckSumStageII('47811243422'));
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::getSerialNumber
     */
    public function testGetSerialNumber() {
        $this->assertEquals('004', $this->estonianPIN->getSerialNumber('48610150042'));
        $this->assertEquals('023', $this->estonianPIN->getSerialNumber('51412120238'));
        $this->assertNotEquals('123', $this->estonianPIN->getSerialNumber('51412120162'));
    }

    /**
     * @expectedException Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::getSerialNumber
     */
    public function testGetSerialNumberExceptionThrowing() {
        $this->estonianPIN->getSerialNumber('');
    }

    /**
     * @covers Lkallas\Estonianpin\EstonianPIN::validateWithExceptions
     */
    public function testValidateWithExceptions() {
        $this->assertTrue($this->estonianPIN->validateWithExceptions('51412120118'));
    }

    /**
     * @expectedException \Lkallas\Estonianpin\Exceptions\InvalidDateException
     * @covers Lkallas\Estonianpin\EstonianPIN::validateWithExceptions
     */
    public function testValidateWithExceptionsWithInvalidDate() {
        $this->estonianPIN->validateWithExceptions('38602301213');
    }

    /**
     * @expectedException \Lkallas\Estonianpin\Exceptions\InvalidCheckSumException
     * @covers Lkallas\Estonianpin\EstonianPIN::validateWithExceptions
     */
    public function testValidateWithExceptionsWithInvalidCheckSum() {
        $this->estonianPIN->validateWithExceptions('50310011213');
    }

    /**
     * @expectedException \Lkallas\Estonianpin\Exceptions\InvalidPersonalIdentificationNrException
     * @covers Lkallas\Estonianpin\EstonianPIN::validateWithExceptions
     */
    public function testValidateWithExceptionsWithInvalidFormat() {
        $this->estonianPIN->validateWithExceptions('812345678910');
    }

}
