<?php

declare(strict_types=1);

namespace voku\value_objects;

use voku\helper\UTF8;

/**
 * @extends AbstractValueObject<string>
 *
 * @immutable
 */
final class ValueObjectUrl extends AbstractValueObject
{
    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if ($value === '' || $value === null) {
            return true;
        }

        if (!UTF8::is_url($value)) {
            return false;
        }

        return parent::validate($value);
    }
}
