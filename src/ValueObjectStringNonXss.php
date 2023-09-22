<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use voku\helper\AntiXSS;
use voku\ValueObjects\exceptions\InvalidValueObjectException;

/**
 * @immutable
 */
final class ValueObjectStringNonXss extends ValueObjectString
{
    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if ($value === '') {
            return true;
        }

        if ($this->isXss($value)) {
            return false;
        }

        return true;
    }

    /**
     * @return ValueObjectStringNonXss
     * @throws InvalidValueObjectException
     */
    public static function createAndRemoveXss(string $string): InterfaceValueObject {
        $antiXssClone = self::getAntiXssInstance();
        $string = $antiXssClone->xss_clean($string);

        return parent::create($string);
    }

    private function isXss(string $string): bool
    {
        $antiXssClone = self::getAntiXssInstance();
        $antiXssClone->xss_clean($string);

        return (bool)$antiXssClone->isXssFound();
    }

    private static function getAntiXssInstance(): AntiXSS
    {
        static $ANTI_XSS = null;
        if ($ANTI_XSS === null) {
            $ANTI_XSS = new AntiXSS();
            $ANTI_XSS->removeEvilAttributes(['style']); // allow style-attributes
        }
        \assert($ANTI_XSS instanceof AntiXSS);

        return clone $ANTI_XSS;
    }
}
