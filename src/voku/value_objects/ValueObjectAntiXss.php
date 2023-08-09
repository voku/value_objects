<?php

declare(strict_types=1);

namespace voku\value_objects;

use voku\helper\AntiXSS;
use voku\helper\UTF8;
use const ENT_QUOTES;
use const ENT_SUBSTITUTE;

/**
 * @immutable
 */
final class ValueObjectAntiXss extends ValueObjectString
{

    /**
     * @param string $value
     *
     * {@inheritdoc}
     */
    public static function create($value): static
    {
        $value = self::anti_xss($value);

        return parent::create($value);
    }

    /**
     * Simple Anti-XSS for html-output.
     *
     * take a look at "https://github.com/voku/anti-xss" for more complex XSS attacks
     *
     * @param string $string
     */
    private static function anti_xss($string, bool $asHtmlOutput = true): string
    {
        static $ANTI_XSS = null;
        if ($ANTI_XSS === null) {
            $ANTI_XSS = new AntiXSS();
            $ANTI_XSS->removeEvilAttributes(['style']); // allow style-attributes
        }
        assert($ANTI_XSS instanceof AntiXSS);

        $antiXssClone = clone $ANTI_XSS;
        /* @noinspection CallableParameterUseCaseInTypeContextInspection */
        $string = $antiXssClone->xss_clean($string);

        if ($asHtmlOutput) {
            $string = UTF8::html_entity_decode((string)$string);
            $string = UTF8::htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
        }

        return (string)$string;
    }
}
