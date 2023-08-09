<?php

declare(strict_types=1);

namespace voku\value_objects;

use const ENT_QUOTES;
use const ENT_SUBSTITUTE;
use voku\helper\UTF8;

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
    public static function create($value): self
    {
        $value = UTF8::html_entity_decode((string) $value);
        $value = UTF8::htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE);

        return parent::create($value);
    }
}
