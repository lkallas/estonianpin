## Süsteemi nõuded

Käesolev rakendus toetab PHP-d alates versioonist **7.0**


## Paigaldus (eeldab Composeri olemasolu)

Lisage järgnev sõltuvus oma projekti `composer.json` faili:

```json
"require": {
    "lkallas/estonianpin": "^1.0"
}
```

Järgnevalt käivitage käsurealt käsk olenevalt composeri paigaldusviisist `composer update` või `php composer.phar update`

Alternatiiv on paigaldada viimane versioon ka käsurealt:

```bash
$ composer require lkallas/estonianpin
```


## API
### EstonianPIN klass

| Funktsioon | Argumendid | Tagastusväärtuse tüüp | Kirjeldus |
| ---------- | ---------- | --------------------- | --------- |
| isValidatedByRegex | isikukood `string` | `boolean` | Teostab regulaaravaldisega kontrolli kas isikukoodi struktuur vastab nõuetele |
| validateWithExceptions | isikukood `string` | `true` eduka valideerimise puhul, ebaõnnestumisel visatakse vastav `exception` | Teostab isikukoodile järgnevad kontrollid: struktuur, sünnikuupäev, kontrollnumbri õigsus |
| validate | isikukood `string` | `boolean` | Teostab isikukoodile järgnevad kontrollid: struktuur, sünnikuupäev, kontrollnumbri õigsus |
| getGender | isikukood `string` | `string` male või female | Tagastab isiku soo isikukoodi alusel |
| getBirthCentury  | isikukood `string` | `int` | Tagastab isiku sünni sajandi isikukoodi alusel |
| getYearOfBirth | isikukood `string` | `int` | Tagastab isiku sünniaasta isikukoodi alusel |
| getMonthOfBirth | isikukood `string` | `int` | Tagastab isiku sünnikuu isikukoodi alusel |
| getDayOfBirth | isikukood `string` | `int` | Tagastab isiku sünnikuupäeva päeva isikukoodi alusel |
| getBirthDateAsDatetimeObj | isikukood `string` | `DateTime` | Tagastab isiku sünnikuupäeva PHP DateTime objektina isikukoodi alusel |
| getCurrentAgeByPIN | isikukood `string` | `DateInterval` | Tagastab isiku vanuse PHP DateInterval objektina isikukoodi alusel |
| getCurrentAgeInYearsByPIN| isikukood `string` | `int` | Tagastab isiku vanuse aastates isikukoodi alusel |
| calculateCheckSum | isikukood `string` | `int` | Arvutab ja tagastab isikukoodi kontrollnumbri |
| calculateCheckSumStageI | isikukood `string` | `int` | Arvutab ja tagastab kontrollnumbri arvutuse tulemuse kasutades arvutustes I astme kaalu |
| calculateCheckSumStageII | isikukood `string` | `int` | Arvutab ja tagastab kontrollnumbri arvutuse tulemuse kasutades arvutustes II astme kaalu |

### Utils klass

| Funktsioon | Argumendid | Tagastusväärtuse tüüp | Kirjeldus |
| ---------- | ---------- | --------------------- | --------- |
| isUnderAge | isikukood `string`, vanusepiir `int` | `boolean` | Kontrollib kas isik on alaealine isikukoodi alusel. Vaikimisi loetakse alaealisseks isikut, kes on vähem kui 18 aastat vana |
| isPensioner | isikukood `string`, vanusepiir `int` | `boolean` | Kontrollib kas isik on pensioniealine isikukoodi alusel. Vaikimisi loetakse pensioniealiseks isikut, kes on vähemalt 65 aastane |
| getGenderAndCenturyIdentificationNumber | sünniaasta `int`, sugu `string`  | `int` | Tagastab soo ja sünnisajandi identifikaatori numbri isiku soo ja sünniaasta alusel |
| getPersonDetailsByPINAsArray | isikukood `string` | `array` | Tagastab assotsiatiivse massiivi isiku andmetega isikukoodi alusel (sugu, sünniaasta, sünnikuu, sünnipäeva ja järjekorranumbri) |
| getPersonDetailsByPIN | isikukood `string` | `stdClass` | Sama mis eelmine aga tagastuse tüüp on objekt |
| generateRandomMalePIN  |  | `string` | Tagastab suvalise korrektse meesterahva isikukoodi |
| generateRandomFemalePIN |  | `string` | Tagastab suvalise korrektse naisterahva isikukoodi |
| generateRandomPIN |  | `string` | Tagastab suvalise korrektse isikukoodi |
| generate | isiku andmed `array` | `string` | Tagastab suvalise isikukoodi sisendandmete alusel |
| generateValidRandomDate |  | `string` | Genereerib suvalise kuupäeva kuni 100 aastat tagasi ja käesoleva kuupäeva vahel pp.kk.aaaa formaadis  |
| generateRandomDateArray | | `array` | Sama, mis eelmine aga tagastuse tüüp on assotsiatiivne massiiv |
| getPinsGeneratorForRange | Vahemiku alguskuupäev `\DateTime`, vahemiku lõppkuupäev `\DateTime` | `\Iterator` | Tagastab generaatori, mis genereerib kõik isikukoodid antud kuupäevade vahemikus. |
