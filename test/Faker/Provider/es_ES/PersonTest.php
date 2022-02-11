<?php

namespace Faker\Test\Provider\es_ES;

use Faker\Provider\es_ES\Person;
use Faker\Test\TestCase;

/**
 * @group legacy
 */
final class PersonTest extends TestCase
{
    public const MAP_LETTERS = 'TRWAGMYFPDXBNJZSQVHLCKE';

    public function testDNI()
    {
        self::assertTrue($this->isValidDNI($this->faker->dni));
    }

    public function testNIE()
    {
        self::assertTrue($this->isValidNie($this->faker->nie));
    }

    // validation taken from http://kiwwito.com/php-function-for-spanish-dni-nie-validation/
    public function isValidDNI($string)
    {
        if (strlen($string) != 9
            || preg_match('/^[XYZ]?([0-9]{7,8})([A-Z])$/i', $string, $matches) !== 1) {
            return false;
        }

        [, $number, $letter] = $matches;

        return strtoupper($letter) === self::MAP_LETTERS[((int) $number) % 23];
    }

    /**
     * @doc https://blog.trescomatres.com/2019/09/php-validar-un-nie/
     * @param string $nif
     * @return bool
     */
    public function isValidNie(string $nif): bool
    {
        if (preg_match('/^[XYZT][0-9][0-9][0-9][0-9][0-9][0-9][0-9][A-Z0-9]/', $nif)) {
            for ($i = 0;$i < 9;++$i) {
                $num[$i] = substr($nif, $i, 1);
            }

            if ($num[8] == substr(
                    self::MAP_LETTERS,
                    substr(str_replace(['X','Y','Z'], [0,1,2], $nif), 0, 8) % 23,
                    1
                )) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function testLicenceCode()
    {
        $validLicenceCodes = ['AM', 'A1', 'A2', 'A', 'B', 'B+E', 'C1', 'C1+E', 'C', 'C+E', 'D1', 'D1+E', 'D', 'D+E'];

        self::assertContains($this->faker->licenceCode, $validLicenceCodes);
    }

    protected function getProviders(): iterable
    {
        yield new Person($this->faker);
    }
}
