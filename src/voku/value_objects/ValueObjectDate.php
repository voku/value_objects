<?php

declare(strict_types=1);

namespace voku\value_objects;

use DateTimeImmutable;
use DateTimeZone;

/**
 * @extends AbstractValueObject<string>
 *
 * @immutable
 */
final class ValueObjectDate extends AbstractValueObject
{
    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        try {
            $dateTime = new \DateTimeImmutable($value . ' 00:00:00');
        } catch (\Exception $e) {
            trigger_error('wrong date (required format: Y-m-d): ' . $value . ' | ' . $e->__toString(), \E_USER_WARNING);

            return false;
        }

        if ($dateTime->format('Y-m-d') !== $value) {
            return false;
        }

        return parent::validate($value);
    }

    public function format(string $format = 'Y-m-d'): string
    {
        return $this->getDateTime()->format($format);
    }

    public function getDateTime(?DateTimeZone $timezone = null): DateTimeImmutable
    {
        return new DateTimeImmutable((string) $this->value(), $timezone);
    }
}
