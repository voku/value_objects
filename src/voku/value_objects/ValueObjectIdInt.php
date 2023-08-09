<?php

declare(strict_types=1);

namespace voku\value_objects;

use voku\value_objects\utils\MathUtils;

/**
 * @template TValue as int
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
    protected function parse($value)
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

        return parent::validate($value);
    }
}
