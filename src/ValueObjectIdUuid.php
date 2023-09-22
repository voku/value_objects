<?php

declare(strict_types=1);

namespace voku\ValueObjects;

/**
 * @extends AbstractValueObject<string>
 *
 * @immutable
 */
class ValueObjectIdUuid extends AbstractValueObject
{
    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (
            !$value
            ||
            !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', (string) $value)
        ) {
            trigger_error('wrong uuid: ' . $value, \E_USER_WARNING);

            return false;
        }

        return true;
    }
}
