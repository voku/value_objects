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
        if (!\is_int($value)) {
            return false;
        }

        /* @phpstan-ignore-next-line | always false in theory */
        if ($value <= 0) {
            return false;
        }

        return true;
    }
}
