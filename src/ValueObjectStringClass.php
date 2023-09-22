<?php

declare(strict_types=1);

namespace voku\ValueObjects;

/**
 * @extends AbstractValueObject<class-string>
 *
 * @immutable
 */
final class ValueObjectStringClass extends AbstractValueObject
{
    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!class_exists($value)) {
            return false;
        }

        return true;
    }
}
