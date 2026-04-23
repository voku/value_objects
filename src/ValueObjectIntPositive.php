<?php

declare(strict_types=1);

namespace voku\ValueObjects;

/**
 * @extends ValueObjectInt<int<1,max>>
 *
 * @immutable
 */
final class ValueObjectIntPositive extends ValueObjectInt
{

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        /* @phpstan-ignore function.alreadyNarrowedType */
        if (!\is_int($value)) {
            return false;
        }

        /* @phpstan-ignore smallerOrEqual.alwaysFalse */
        if ($value <= 0) {
            return false;
        }

        return true;
    }
}
