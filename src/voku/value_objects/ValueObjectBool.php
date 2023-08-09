<?php

declare(strict_types=1);

namespace voku\value_objects;

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
     */
    public static function create($value): self
    {
        if ($value === 0 || $value === '0' || $value === '' || $value === 'f' || $value === 'false') {
            $value = false;
        } elseif ($value === 1 || $value === '1' || $value === 't' || $value === 'true') {
            $value = true;
        }

        return parent::create($value);
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

    public function getValue(): bool
    {
        return (bool) (string) $this;
    }

    public function isTrue(): bool
    {
        return $this->getValue() === true;
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
        if (!\is_bool($value)) {
            return false;
        }

        return parent::validate($value);
    }
}
