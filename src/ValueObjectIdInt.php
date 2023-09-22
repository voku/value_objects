<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use voku\ValueObjects\utils\MathUtils;

/**
 * @template TValue as int|numeric-string
 *
 * @extends AbstractValueObject<TValue>
 *
 * @immutable
 */
class ValueObjectIdInt extends AbstractValueObject
{
    /**
     * {@inheritdoc}
     */
    protected function parseAfterValidation($value)
    {
        return (int) $value;
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!MathUtils::is_numeric_int($value)) {
            trigger_error('wrong integer id: ' . $value, \E_USER_WARNING);

            return false;
        }

        return true;
    }
}
