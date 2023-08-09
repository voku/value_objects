<?php

declare(strict_types=1);

namespace voku\value_objects;

use Stringy\Stringy;

/**
 * @extends AbstractValueObject<string>
 *
 * @immutable
 */
final class ValueObjectEmail extends AbstractValueObject
{

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!Stringy::create($value)->isEmail()) {
            trigger_error('not a valid email address: ' . $value, E_USER_WARNING);

            return false;
        }

        return parent::validate($value);
    }

}
