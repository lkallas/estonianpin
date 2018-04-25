## Requirements

This library supports PHP from version **7.0**


## Installing (presuming Composer is installed)

Add the following to your `composer.json` file:

```json
"require": {
    "lkallas/estonianpin": "^1.0"
}
```

Give the command depending on the composer installation `composer update` or `php composer.phar update`

Alternatively you can install by simply using command:

```bash
$ composer require lkallas/estonianpin
```


## API
### EstonianPIN class

| Function | Arguments | Return type | Description |
| -------- | --------- | ----------- | ----------- |
| isValidatedByRegex | Estonian Personal Identification Code `string` | `boolean` | Checks whether the Personal Identification Code format passes regular expression |
| validateWithExceptions | Estonian Personal Identification Code `string` | `true` on successful validation, otherwise corresponding `exception` is thrown | Performs various Estonian Personal Identification Code validations: structure, date of birth and checksum validation |
| validate | Estonian Personal Identification Code `string` | `boolean` | Performs various Estonian Personal Identification Code validations: structure, date of birth and checksum validation |
| getGender | Estonian Personal Identification Code `string` | `string` male or female | Returns person's gender based on Estonian Personal Identification Code |
| getBirthCentury  | Estonian Personal Identification Code `string` | `int` | Returns person's birth century based on Estonian Personal Identification Code |
| getYearOfBirth | Estonian Personal Identification Code `string` | `int` | Returns person's year of birth based on Estonian Personal Identification Code |
| getMonthOfBirth | Estonian Personal Identification Code `string` | `int` | Returns person's month of birth based on Estonian Personal Identification Code |
| getDayOfBirth | Estonian Personal Identification Code `string` | `int` | Returns person's day of birth based on Estonian Personal Identification Code |
| getBirthDateAsDatetimeObj | Estonian Personal Identification Code `string` | `DateTime` | Returns person's birthdate as PHP DateTime object based on Estonian Personal Identification Code |
| getCurrentAgeByPIN | Estonian Personal Identification Code `string` | `DateInterval` | Returns person's age as PHP DateInterval object based on Estonian Personal Identification Code |
| getCurrentAgeInYearsByPIN| Estonian Personal Identification Code `string` | `int` | Returns person's age in years based on Estonian Personal Identification Code |
| calculateCheckSum | Estonian Personal Identification Code `string` | `int` | Calculates and returns Estonian Personal Identification Code checksum |
| calculateCheckSumStageI | Estonian Personal Identification Code `string` | `int` | Calculates and returns the checksum which is calculated using stage I multpilier sequence  |
| calculateCheckSumStageII | Estonian Personal Identification Code `string` | `int` | Calculates and returns the checksum which is calculated using stage II multpilier sequence |

### Utils class

| Function | Arguments | Return type | Description |
| -------- | --------- | ----------- | ----------- |
| isUnderAge | Estonian Personal Identification Code `string`, agelimit `int` | `boolean` | Checks if the person is a minor based on Estonian Personal Identification Code. Default age limit is 18 |
| isPensioner | Estonian Personal Identification Code `string`, agelimit `int` | `boolean` | Checks if the person is a pensioner based on Estonian Personal Identification Code. Default age lower age limit is 65 |
| getGenderAndCenturyIdentificationNumber | year of birth `int`, gender `string`  | `int` | Returns person's gender/birth century identification number based on year of birth and gender |
| getPersonDetailsByPINAsArray | Estonian Personal Identification Code `string` | `array` | Returns associative array with person details based on Estonian Personal Identification Code (gender, year of birth, month of birth, day of birth and serial number) |
| getPersonDetailsByPIN | Estonian Personal Identification Code `string` | `stdClass` | Same as previous but the return type is an object |
| generateRandomMalePIN  |  | `string` | Generates random  Estonian Personal Identification Code for male person |
| generateRandomFemalePIN |  | `string` | Generates random  Estonian Personal Identification Code for female person |
| generateRandomPIN |  | `string` | Generates random  Estonian Personal Identification Code |
| generate | isiku andmed `array` | `string` | Generates a Estonian Personal Identification Code based on inserted data |
| generateValidRandomDate |  | `string` | Generates random date between current date and the date up to 100 years back. The format will be dd.mm.yyyy  |
| generateRandomDateArray | | `array` | Same as the previous but the return type is an array |
| getPinsGeneratorForRange | Range begin date `\DateTime`, range end date `\DateTime` | `\Iterator` | Returns generator for all identification numbers in range |
