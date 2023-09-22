<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use voku\helper\EmailCheck;

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
        if (!EmailCheck::isValid((string)$value)) {
            trigger_error('not a valid email address: ' . $value, \E_USER_WARNING);

            return false;
        }

        return true;
    }

    /**
     * @return false|array{local:string,domain:string}
     */
    public function getMailParts() {
        return EmailCheck::getMailParts($this->value());
    }
}
