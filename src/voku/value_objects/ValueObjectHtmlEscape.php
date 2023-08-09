<?php

declare(strict_types=1);

namespace voku\value_objects;

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
     */
    public static function create($value): static
    {
        $value = UTF8::html_entity_decode((string)$value);
        $value = UTF8::htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE);

        return parent::create($value);
    }

}
