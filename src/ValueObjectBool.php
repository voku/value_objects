<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use voku\ValueObjects\exceptions\InvalidValueObjectException;

/**
 * @extends AbstractValueObject<bool>
 *
 * @immutable
 */
final class ValueObjectBool extends AbstractValueObject
{
    /**
     * {@inheritdoc}
     *
     * @phpstan-param 0|'0'|''|1|'1'|'f'|'t'|'false'|'true'|false|true $value
     *
     * @phpstan-return ValueObjectBool
     */
    public static function create($value): InterfaceValueObject
    {
        /* @phpstan-ignore-next-line | ok here because of "parseBeforeValidation" */
        return parent::create($value);
    }

    protected function parseBeforeValidation($value)
    {
        if ($value === 0 || $value === '0' || $value === '' || $value === 'f' || $value === 'false') {
            $value = false;
        } elseif ($value === 1 || $value === '1' || $value === 't' || $value === 'true') {
            $value = true;
        }

        return $value;
    }

    /**
     * @return static
     */
    public static function createTrue(): self
    {
        return static::create(true);
    }

    /**
     * @return static
     */
    public static function createFalse(): self
    {
        return static::create(false);
    }

    public function isTrue(): bool
    {
        return $this->value() === true;
    }

    public function isFalse(): bool
    {
        return $this->value() === false;
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!\is_bool($value)) {
            return false;
        }

        return true;
    }
}
