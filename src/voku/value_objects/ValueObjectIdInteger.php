<?php

declare(strict_types=1);

namespace voku\value_objects;

/**
 * @template TValue as int
 * @extends AbstractValueObject<TValue>
 *
 * @immutable
 */
abstract class ValueObjectIdInteger extends AbstractValueObject
{

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if ($value != (int)$value) {
            trigger_error('wrong integer id: ' . $value, E_USER_WARNING);

            return false;
        }

        return parent::validate($value);
    }

}
