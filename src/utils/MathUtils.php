<?php

declare(strict_types=1);

namespace voku\ValueObjects\utils;

use NumberFormatter;
use voku\helper\AntiXSS;
use voku\helper\UTF8;
use voku\ValueObjects\AbstractValueObject;
use voku\ValueObjects\InterfaceValueObject;
use voku\ValueObjects\ValueObjectInt;
use voku\ValueObjects\ValueObjectNumeric;
use function fmod;
use function round;

final class MathUtils
{
    /**
     * @param numeric|ValueObjectInt|ValueObjectNumeric $numeric
     */
    public static function numberOfDecimals($numeric, bool $removeRightZero = false): int
    {
        if ($numeric instanceof ValueObjectNumeric || $numeric instanceof ValueObjectInt) {
            $numeric = $numeric->value();
        }

        if ((int) $numeric === $numeric) {
            return 0;
        }

        $numeric = self::numericToNumericString($numeric);
        if (!self::is_numeric($numeric)) {
            trigger_error('$numeric is not numeric or has spaces: "' . print_r($numeric, true) . '"', \E_USER_WARNING);

            return 0;
        }

        $value = strrpos($numeric, '.');
        if ($value === false) {
            return 0;
        }

        if ($removeRightZero) {
            $numeric = rtrim($numeric, '0');
        }

        return \strlen($numeric) - $value - 1;
    }

    /**
     * @param numeric|ValueObjectInt|ValueObjectNumeric $numeric
     *
     * @return numeric-string
     */
    public static function numericToNumericString($numeric): string
    {
        $string = (string) $numeric;
        if ($string === '') {
            return '0';
        }

        if (
            UTF8::str_contains($string, 'E', false)
            &&
            preg_match('~\.(\d+)E([+-])?(\d+)~i', $string, $matches)
        ) {
            if ($matches[2] === '-') {
                $decimals = \strlen($matches[1]) + (int) $matches[3];
            } else {
                $decimals = 0;
            }

            if ($numeric instanceof InterfaceValueObject) {
                $numeric = (float)$numeric->value();
            } else {
                $numeric = (float)$numeric;
            }

            $string = number_format($numeric, $decimals, '.', '');
        }

        assert(is_numeric($string) && is_string($string));

        return $string;
    }

    /**
     * @param scalar|null $value
     */
    public static function is_numeric($value, bool $checkForSpaces = false): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        if ($checkForSpaces && trim((string) $value, ' ') !== (string) $value) {
            return false;
        }

        return true;
    }

    /**
     * @param numeric|ValueObjectInt|ValueObjectNumeric $value
     *
     * @return numeric-string
     *
     * @phpstan-param 1|2|3|4 $mode
     *                              <p>PHP_ROUND_*</p>
     */
    public static function round($value, int $precision, int $mode = \PHP_ROUND_HALF_UP)
    {
        $value = self::numericToNumericString($value);

        return ValueObjectNumeric::create($value)->round($precision, $mode)->value();
    }

    /**
     * Simple Anti-XSS for html-output.
     *
     * take a look at "https://github.com/voku/anti-xss" for more complex XSS attacks
     *
     * @param string $string
     */
    public static function anti_xss($string, bool $asHtmlOutput = true): string
    {
        static $ANTI_XSS = null;
        if ($ANTI_XSS === null) {
            $ANTI_XSS = new AntiXSS();
            $ANTI_XSS->removeEvilAttributes(['style']); // allow style-attributes
        }
        \assert($ANTI_XSS instanceof AntiXSS);

        $antiXssClone = clone $ANTI_XSS;
        /* @noinspection CallableParameterUseCaseInTypeContextInspection */
        $string = $antiXssClone->xss_clean($string);

        if ($asHtmlOutput) {
            $string = UTF8::html_entity_decode((string) $string);
            $string = UTF8::htmlspecialchars($string, \ENT_QUOTES | \ENT_SUBSTITUTE);
        }

        return (string) $string;
    }

    /**
     * Formats a number in a folkloric way.
     *
     * WARNING: this method return e.g.: INPUT => OUTPUT
     *
     * for e.g. [de-DE] locale:
     * (string) "16.5"      => "16,50"
     * (string) "16,5"      => "16,00" // wrong input
     *
     * for e.g. [en-US] locale:
     * (string) "16.5"      => "16.50"
     * (string) "16,5"      => "16.00" // wrong input
     *
     * @param InterfaceValueObject|float|int|string $value <p>Value to be
     *                                                                                      formatted.</p>
     * @param int $precision <p>Number of decimal
     *                                                                                      places. (Default: 2, Auto:
     *                                                                                      -1)</p>
     * @param bool $showMore <p>Show all relevant
     *                                                                                      decimal places if more
     *                                                                                      decimal places. exist than
     *                                                                                      specified. (Default:
     *                                                                                      false)</p>
     *
     * @phpstan-param ''|numeric|InterfaceValueObject<numeric>|ValueObjectNumeric $value
     * @phpstan-param int $precision
     *
     * @return false|string
     */
    public static function i18n_number_format(
        $value,
        string $activeLocale = 'en-US',
        int $precision = 2,
        bool $showMore = false,
        bool $sendWarningOnError = true
    ) {
        $valueOrig = $value;

        if ($value instanceof AbstractValueObject) {
            /* @noinspection CallableParameterUseCaseInTypeContextInspection */
            $value = $value->value();
        }

        if ($precision === -1) {
            $precision = \strlen((string) fmod((float) $value, 1)) - 2;
            if ($precision < 0) {
                $precision = 0;
            }
        }

        // fallback for e.g. ''
        if (!$value) {
            $value = 0.0;
        }

        if ($sendWarningOnError && !self::is_numeric($value)) {
            trigger_error('Wrong input for "i18n_number_format()", please use a numeric value: ' . print_r($value, true) . ' | ' . print_r($valueOrig, true), \E_USER_WARNING);

            return false;
        }

        if (self::is_numeric_int($value)) {
            $value = (int) $value;
        } elseif (self::is_numeric($value)) {
            $value = (float) $value;
        } else {
            if ($sendWarningOnError) {
                trigger_error('Wrong input for "i18n_number_format()", please use a numeric value: ' . print_r($value, true) . ' | ' . print_r($valueOrig, true), \E_USER_WARNING);
            }

            return false;
        }

        if ($showMore) {
            $parts = explode('.', (string) $value);
            if (isset($parts[1])) {
                $fraction = rtrim($parts[1], '0');
                if (\strlen($fraction) > $precision) {
                    $precision = \strlen($fraction);
                }
            }

            $valueBackup = (float) $value;
            $value = round((float) $value, $precision);
            // INFO: do not show zero values if the value is not zero
            while ($valueBackup > 0 && $value === 0.0) {
                $value = round((float) $valueBackup, ++$precision);
            }
        }

        $numberFormatter = new NumberFormatter($activeLocale, NumberFormatter::DECIMAL);
        $numberFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);
        $numberFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $precision);
        $result = $numberFormatter->format($value, NumberFormatter::TYPE_DOUBLE);
        if ($result === false) {
            if ($sendWarningOnError) {
                trigger_error('Wrong input for "i18n_number_format()", please use a numeric value: ' . print_r($value, true) . ' | ' . print_r($valueOrig, true), \E_USER_WARNING);
            }

            return false;
        }

        return $result;
    }

    /**
     * @param scalar|null $value
     */
    public static function is_numeric_int($value, bool $checkForSpaces = false): bool
    {
        $regex = '#^-?\d+$#';

        return self::is_numeric($value, $checkForSpaces)
               &&
               (!$checkForSpaces ? preg_match($regex, trim((string)$value, ' ')) : preg_match($regex, (string)$value));
    }

    /**
     * WARNING: this method return e.g.: INPUT => OUTPUT
     *
     * for e.g. [de-DE] locale:
     * - (string) "16,5"   => "16.5"
     * - (string) "16.5"   => NULL
     * - (string) "1.000"  => "1000"
     *
     * for e.g. [en-US] locale:
     * - (string) "16,5"   => NULL
     * - (string) "16.5"   => "16.5"
     * - (string) "1.000"  => "1"
     *
     * @param string|null                   $value
     * @param mixed|null                    $fallback
     *
     * @return mixed|string|null
     *
     * @template TiParseNumberFallback
     *
     * @phpstan-param TiParseNumberFallback $fallback
     *
     * @phpstan-return numeric-string|TiParseNumberFallback
     */
    public static function i18n_number_parse(
        $value,
        string $activeLocale = 'en-US',
        $fallback = null,
        bool $sendWarningOnError = true
    ) {
        $valueOrig = $value;

        if (\is_string($value)) {
            $value = trim(
                trim($value),
                ' ^.,€$"\'!%?()[]/\\' // hack for bad inputs
            );
        }

        // INFO: an empty string and null is not numeric, so we return the fallback without reporting a warning here
        if ($value === null || $value === '') {
            return $fallback;
        }

        $numberFormatter = new NumberFormatter($activeLocale, NumberFormatter::DECIMAL);
        $number = (string) $numberFormatter->parse((string) $value, NumberFormatter::TYPE_DOUBLE);

        if (self::is_numeric($number)) {
            \assert(is_numeric($number));

            return $number;
        }

        if ($sendWarningOnError) {
            trigger_error('Wrong input for "i18n_number_parse()", please use a non-numeric value: ' . $value . ' | ' . $valueOrig, \E_USER_WARNING);

            return $fallback;
        }

        return $fallback;
    }
}
