<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use voku\helper\UTF8;
use const ENT_QUOTES;
use const ENT_SUBSTITUTE;

/**
 * @immutable
 */
final class ValueObjectHtmlEscape extends ValueObjectString
{
    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public static function create($value): InterfaceValueObject
    {
        $value = UTF8::html_entity_decode((string) $value);
        $value = UTF8::htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE);

        return parent::create($value);
    }
}
