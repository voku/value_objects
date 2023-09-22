<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use voku\helper\UTF8;
use const JSON_THROW_ON_ERROR;

/**
 * @extends AbstractValueObject<string>
 *
 * @immutable
 */
final class ValueObjectJson extends AbstractValueObject
{
    /**
     * @param array<array-key, mixed>|object $value
     *
     * @return static
     */
    public static function createWithJsonEncode($value)
    {
        return parent::create(json_encode($value, JSON_THROW_ON_ERROR));
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): bool
    {
        if (!UTF8::is_json($value)) {
            return false;
        }

        return true;
    }
}
