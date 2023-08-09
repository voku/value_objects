<?php

declare(strict_types=1);

namespace voku\value_objects;

use const JSON_THROW_ON_ERROR;
use voku\helper\UTF8;

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
    public static function create($value): self
    {
        if ($value === 'null') { // null is a valid json value, but we try to validate php interpretation of it
            $value = null;
        }

        return parent::create($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (
            $value !== null
            &&
            !UTF8::is_json($value)
        ) {
            return false;
        }

        return parent::validate($value);
    }
}
