<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\exceptions\InvalidValueObjectException;
use voku\value_objects\utils\MathUtils;
use voku\value_objects\ValueObjectNumeric;

final class ValueObjectNumericTest extends TestCase
{
    private int $scale = 10;

    public function testSimple(): void
    {
        $numeric = ValueObjectNumeric::create(1);
        self::assertSame('4.1400000000', $numeric->add(3.14)->getValue());

        // --

        $numeric = ValueObjectNumeric::create(MathUtils::i18n_number_parse('1.3'));
        self::assertSame('1.0000000000', $numeric->sub(0.3)->getValue());
    }

    public function testSimpleFail(): void
    {
        $this->expectException(InvalidValueObjectException::class);
        $this->expectExceptionMessage('The value "test" is not correct for: voku\value_objects\ValueObjectNumeric');

        ValueObjectNumeric::create('test');
    }

    /**
     * @dataProvider scientificNotationExamples
     */
    public function itSupportsScientificNotation($scientificNotationExamples0, $scientificNotationExamples1): void
    {
        self::assertEquals($scientificNotationExamples1, ValueObjectNumeric::create($scientificNotationExamples0)->getValue());
    }

    public function scientificNotationExamples(): array
    {
        return [
            ['-6.232E-6', '-0.000006232'],
            ['-6.232E6', '-6232000'],
            ['-0.25E5', '-25000'],
            ['-0.254345466766E-5', '-0.00000254345466766'],
            ['12.322', '12.322'],
            ['-12.322', '-12.322'],
        ];
    }

    /**
     * @dataProvider twoFloatsAndScale
     */
    public function testAdd($number1, $number2, ?int $scale): void
    {
        $bcScale = $scale ?? $this->scale;
        self::assertSame(
            bcadd($number1, $number2, $bcScale),
            (string)ValueObjectNumeric::create($number1)->add($number2, $scale)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     */
    public function testSub($number1, $number2, ?int $scale): void
    {
        $bcScale = $scale ?? $this->scale;
        self::assertSame(
            bcsub($number1, $number2, $bcScale),
            (string)ValueObjectNumeric::create($number1)->sub($number2, $scale)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     */
    public function testMul($number1, $number2, ?int $scale): void
    {
        $bcScale = $scale ?? $this->scale;
        self::assertSame(
            bcmul($number1, $number2, $bcScale),
            (string)ValueObjectNumeric::create($number1)->mul($number2, $scale)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     */
    public function testDiv($number1, $number2, ?int $scale): void
    {
        $bcScale = $scale ?? $this->scale;
        self::assertSame(
            bcdiv($number1, $number2, $bcScale),
            (string)ValueObjectNumeric::create($number1)->div($number2, $scale)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     */
    public function testDivAgain($number1, $number2, $scale): void
    {
        $bcScale = $scale ?? $this->scale;
        self::assertSame(
            bcdiv($number1, $number2, $bcScale),
            (string)ValueObjectNumeric::create($number1, $bcScale)->div($number2)
        );

        // ---

        self::assertSame(
            bcadd(bcdiv($number1, $number2, 5), $number2, 1),
            (string)ValueObjectNumeric::create($number1, 1)->div($number2, 5)->add($number2)
        );
    }

    /**
     * @dataProvider powData
     */
    public function testPow($number1, $number2, ?int $scale): void
    {
        $bcScale = $scale ?? $this->scale;
        self::assertSame(
            bcpow($number1, $number2, $bcScale),
            (string)ValueObjectNumeric::create($number1)->pow($number2, $scale)
        );
    }

    /**
     * @dataProvider modData
     */
    public function testMod($number1, $number2): void
    {
        self::assertSame(
            bcmod($number1, $number2),
            (string)ValueObjectNumeric::create($number1)->mod($number2)
        );
    }

    /**
     * @dataProvider twoFloatsAndScale
     */
    public function testSqrt($number1, $number2, ?int $scale): void
    {
        $bcScale = $scale ?? $this->scale;
        self::assertSame(
            bcsqrt($number1, $bcScale),
            (string)ValueObjectNumeric::create($number1)->sqrt($scale)
        );
    }

    /**
     * @dataProvider powModData
     */
    public function testPowMod($number1, $number2, $number3, ?int $scale): void
    {
        $bcScale = $scale ?? $this->scale;
        self::assertSame(
            bcpowmod($number1, $number2, $number3, $bcScale),
            (string)ValueObjectNumeric::create($number1)->powmod($number2, $number3, $scale)
        );
    }

    public function twoFloatsAndScale(): array
    {
        return [
            'non-empty scale' => [
                $this->randomFloat(),
                $this->randomFloat(),
                3,
            ],
            'null scale'      => [
                $this->randomFloat(),
                $this->randomFloat(),
                null,
            ],
            'scale 0'         => [
                $this->randomFloat(),
                $this->randomFloat(),
                0,
            ],
        ];
    }

    private function randomFloat(): string
    {
        return (string)(random_int(1, mt_getrandmax()) / 1000);
    }

    public function modData(): array
    {
        return [
            'non-empty scale' => [
                $this->randomFloat(),
                random_int(1, 100),
            ],
            'null scale'      => [
                $this->randomFloat(),
                random_int(1, 100),
            ],
            'scale 0'         => [
                $this->randomFloat(),
                random_int(1, 100),
            ],
        ];
    }

    public function powData(): array
    {
        return [
            'non-empty scale' => [
                $this->randomFloat(),
                random_int(1, 100),
                3,
            ],
            'null scale'      => [
                $this->randomFloat(),
                random_int(1, 100),
                null,
            ],
            'scale 0'         => [
                $this->randomFloat(),
                random_int(1, 100),
                0,
            ],
        ];
    }

    public function powModData(): array
    {
        return [
            'non-empty scale' => [
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
                3,
            ],
            'null scale'      => [
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
                null,
            ],
            'scale 0'         => [
                random_int(1, 100),
                random_int(1, 100),
                random_int(1, 100),
                0,
            ],
        ];
    }

    public function testNumberFormatDe(): void
    {
        $testArray = [
            '0.0'     => '0,00',
            ''        => '0,00',
            '0.000'   => '0,00',
            0         => '0,00',
            11        => '11,00',
            '12'      => '12,00',
            '0.0100'  => '0,01',
            '1.0000'  => '1,00',
            '-1.0000' => '-1,00',
            '16.5'    => '16,50',
            '1.000'   => '1,00',
            '-0.010'  => '-0,01',
            '0.010'   => '0,01',
            '15.000'  => '15,00',
        ];

        foreach ($testArray as $input => $testResult) {
            self::assertSame($testResult, ValueObjectNumeric::create($input)->i18n_number_format('de-DE', 2, false, false), 'tested: ' . $input);
        }

        self::assertSame('0,00', MathUtils::i18n_number_format(null, 'de-DE'));

        self::assertSame(false, @MathUtils::i18n_number_format('385.207.00', 'de-DE', 2, false, false));          // wrong input (non-numeric) => wrong output
        self::assertSame('385,21', MathUtils::i18n_number_format((float)'385.207.00', 'de-DE', 2, false, false)); // non-numeric can not be correctly cast to float

        self::assertSame('0,00', MathUtils::i18n_number_format(0.0000, 'de-DE'));
        self::assertSame('1,00', MathUtils::i18n_number_format(1.000, 'de-DE'));
        self::assertSame('1,10', MathUtils::i18n_number_format(1.10, 'de-DE'));
        self::assertSame('1,10', MathUtils::i18n_number_format(1.100, 'de-DE'));

        // -------------------------------------------------------------------------

        self::assertSame('1,12345', MathUtils::i18n_number_format(1.12345, 'de-DE', -1)); // auto
        self::assertSame('1,13', MathUtils::i18n_number_format(1.1294500003, 'de-DE', 2));
        self::assertSame('1,1294500003', MathUtils::i18n_number_format(1.129450000300, 'de-DE', 2, true));
        self::assertSame('1', MathUtils::i18n_number_format(1.12345, 'de-DE', 0));
        self::assertSame('1,1', MathUtils::i18n_number_format(1.12345, 'de-DE', 1));
        self::assertSame('1,12', MathUtils::i18n_number_format(1.12345, 'de-DE', 2));
        self::assertSame('1,123', MathUtils::i18n_number_format(1.12345, 'de-DE', 3));
    }

    public function testNumberFormatEn(): void
    {
        $testArray = [
            '0.0'     => '0.00',
            ''        => '0.00',
            '0.000'   => '0.00',
            0         => '0.00',
            11        => '11.00',
            '12'      => '12.00',
            '0.0100'  => '0.01',
            '1.0000'  => '1.00',
            '-1.0000' => '-1.00',
            '16.5'    => '16.50',
            '1.000'   => '1.00',
            '-0.010'  => '-0.01',
            '0.010'   => '0.01',
            '15.000'  => '15.00',
            '15,500'  => false,
        ];

        foreach ($testArray as $input => $testResult) {
            self::assertSame($testResult, MathUtils::i18n_number_format($input, 'en-US', 2, false, false), 'tested: ' . $input);
        }

        self::assertSame('0.00', MathUtils::i18n_number_format(null));

        self::assertSame(false, MathUtils::i18n_number_format('385.207.00', 'en-US', 2, false, false));           // wrong input (non-numeric) => wrong output
        self::assertSame('385.21', MathUtils::i18n_number_format((float)'385.207.00', 'en-US', 2, false, false)); // non-numeric can not be correctly cast to float

        self::assertSame('0.00', MathUtils::i18n_number_format(0.0000));
        self::assertSame('1.00', MathUtils::i18n_number_format(1.000));
        self::assertSame('1.10', MathUtils::i18n_number_format(1.10));
        self::assertSame('1.10', MathUtils::i18n_number_format(1.100));

        // -------------------------------------------------------------------------

        self::assertSame('1.12345', MathUtils::i18n_number_format(1.12345, 'en-US', -1)); // auto
        self::assertSame('1', MathUtils::i18n_number_format(1.12345, 'en-US', 0));
        self::assertSame('1.1', MathUtils::i18n_number_format(1.12345, 'en-US', 1));
        self::assertSame('1.12', MathUtils::i18n_number_format(1.12345, 'en-US', 2));
        self::assertSame('1.123', MathUtils::i18n_number_format(1.12345, 'en-US', 3));
    }

    public function testParseNumberDe(): void
    {
        $testArray = [
            'test'     => null,
            null       => null,
            0          => '0',
            11         => '11',
            '12'       => '12',
            '16,5'     => '16.5',
            '16,'      => '16',
            '16.'      => '16',
            '16,0 €'   => '16',
            '^16,0'    => '16',
            '16,50'    => '16.5',
            '0,0100'   => '0.01',
            '1,0000'   => '1',
            '0,290'    => '0.29',
            '1.000,0'  => '1000',
            '-1.000,0' => '-1000',
            '29.87752' => null,
            'x'        => null,
            '2.500'    => '2500',
            // --------------------
            '18,988'   => '18.988',
            '18.988'   => '18988', // wrong value, if we call it twice
            // --------------------
        ];

        foreach ($testArray as $input => $testResult) {
            self::assertSame($testResult, MathUtils::i18n_number_parse($input, 'de-DE', null, false), 'tested: ' . $input);
        }

        $testArray = [
            '0.000'     => '0',
            'test'      => null,
            null        => null,
            0           => '0',
            11          => '11',
            '0.0100'    => null,
            '1.0000'    => null,
            '-1.0000'   => null,
            '16.5'      => null,
            '16,5'      => '16.5',
            '1.000'     => '1000',
            '-0.010'    => '-10',
            '0.010'     => '10',
            '0,0100'    => '0.01',
            '1,0000'    => '1',
            '0,290'     => '0.29',
            '1.000,0'   => '1000',
            '-1.000,0'  => '-1000',
            ' 0,290'    => '0.29',
            '1.000,0 '  => '1000',
            ' 1.000,0 ' => '1000',
        ];

        foreach ($testArray as $input => $testResult) {
            self::assertSame($testResult, MathUtils::i18n_number_parse((string)$input, 'de-DE', null, false), 'tested: ' . $input);
        }

        self::assertSame(null, MathUtils::i18n_number_parse('', 'de-DE', null, false));
        self::assertSame('1', MathUtils::i18n_number_parse((string)1.000, 'de-DE'));
        self::assertSame('1000', MathUtils::i18n_number_parse((string)'1.000', 'de-DE'));
        self::assertSame(null, MathUtils::i18n_number_parse((string)0.1, 'de-DE', null, false));
        self::assertSame('0', MathUtils::i18n_number_parse((string)0.0000, 'de-DE'));
        self::assertSame('1', MathUtils::i18n_number_parse((string)1.000, 'de-DE'));
        self::assertSame(null, MathUtils::i18n_number_parse((string)1.10, 'de-DE', null, false));
        self::assertSame(null, MathUtils::i18n_number_parse((string)1.100, 'de-DE', null, false));
    }

    public function testParseNumberEn(): void
    {
        $testArray = [
            'test'     => null,
            null       => null,
            0          => '0',
            11         => '11',
            '12'       => '12',
            '16.5'     => '16.5',
            '16,'      => '16',
            '16.'      => '16',
            '16.0 €'   => '16',
            '^16.0'    => '16',
            '16.50'    => '16.5',
            '0.0100'   => '0.01',
            '1.0000'   => '1',
            '0.290'    => '0.29',
            '1,000.0'  => '1000',
            '-1,000.0' => '-1000',
            '29,87752' => null,
            'x'        => null,
            '2,500'    => '2500',
            // --------------------
            '18.988'   => '18.988',
            '18,988'   => '18988', // wrong value, if we call it twice
            // --------------------
        ];

        foreach ($testArray as $input => $testResult) {
            self::assertSame($testResult, MathUtils::i18n_number_parse($input, 'en-US', null, false), 'tested: ' . $input);
        }

        $testArray = [
            '0.000'     => '0',
            'test'      => null,
            null        => null,
            0           => '0',
            11          => '11',
            '0.0100'    => '0.01',
            '1.0000'    => '1',
            '-1.0000'   => '-1',
            '16.5'      => '16.5',
            '16,5'      => null,
            '1.000'     => '1',
            '-0.010'    => '-0.01',
            '0.010'     => '0.01',
            '0,0100'    => null,
            '1,0000'    => null,
            '0,290'     => '290',
            '1.000,0'   => '1',
            '-1.000,0'  => '-1',
            ' 0,290'    => '290',
            '1.000,0 '  => '1',
            ' 1.000,0 ' => '1',
        ];

        foreach ($testArray as $input => $testResult) {
            self::assertSame($testResult, MathUtils::i18n_number_parse((string)$input, 'en-US', null, false), 'tested: ' . $input);
        }

        self::assertSame(null, MathUtils::i18n_number_parse('', 'en-US', null, false));
        self::assertSame('1', MathUtils::i18n_number_parse((string)1.000));
        self::assertSame('1', MathUtils::i18n_number_parse((string)'1.000'));
        self::assertSame('0.1', MathUtils::i18n_number_parse((string)0.1));
        self::assertSame('0', MathUtils::i18n_number_parse((string)0.0000));
        self::assertSame('1', MathUtils::i18n_number_parse((string)1.000));
        self::assertSame('1.1', MathUtils::i18n_number_parse((string)1.10));
        self::assertSame('1.1', MathUtils::i18n_number_parse((string)1.100));
    }

}
