<?php

declare(strict_types=1);

namespace voku\value_objects;

use function is_bool;

/**
 * @extends AbstractValueObject<bool>
 *
 * @immutable
 */
final class ValueObjectBool extends AbstractValueObject
{

    public static function createFalse(): static
    {
        return static::create(false);
    }

    /**
     * {@inheritdoc}
     *
     * @phpstan-param 0|'0'|''|1|'1'|'f'|'t'|'false'|'true'|false|true $value
     */
    public static function create($value): static
    {
        if ($value === 0 || $value === '0' || $value === '' || $value === 'f' || $value === 'false') {
            $value = false;
        } elseif ($value === 1 || $value === '1' || $value === 't' || $value === 'true') {
            $value = true;
        }

        return parent::create($value);
    }

    public static function createTrue(): static
    {
        return static::create(true);
    }

    public function isTrue(): bool
    {
        return $this->getValue() === true;
    }

    public function getValue(): bool
    {
        return (bool)(string)$this;
    }

    public function isFalse(): bool
    {
        return $this->getValue() === false;
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if (!is_bool($value)) {
            return false;
        }

        return parent::validate($value);
    }

}
