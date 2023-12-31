<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use DateTimeImmutable;
use DateTimeZone;
use Exception;

/**
 * @extends AbstractValueObject<string>
 *
 * @immutable
 */
final class ValueObjectDateTime extends AbstractValueObject
{
    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        try {
            $dateTime = new DateTimeImmutable((string) $value);
        } catch (Exception $e) {
            trigger_error('wrong date time (required format: Y-m-d H:i:s): ' . $value . ' | ' . $e->__toString(), \E_USER_WARNING);

            return false;
        }

        if ($dateTime->format('Y-m-d H:i:s') !== $value) {
            return false;
        }

        return true;
    }

    public function format(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->getDateTime()->format($format);
    }

    public function getDateTime(?DateTimeZone $timezone = null): DateTimeImmutable
    {
        return new DateTimeImmutable((string) $this->value(), $timezone);
    }
}
