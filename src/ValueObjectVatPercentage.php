<?php

declare(strict_types=1);

namespace voku\ValueObjects;

use voku\ValueObjects\utils\MathUtils;

/**
 * @extends AbstractValueObject<numeric>
 *
 * @immutable
 */
final class ValueObjectVatPercentage extends AbstractValueObject
{
    /**
     * @param numeric-string|ValueObjectNumeric $net_price
     *
     * @return numeric-string
     */
    public function getGross($net_price, int $precision = 3): string
    {
        if (!$net_price instanceof ValueObjectNumeric) {
            $net_price = ValueObjectNumeric::create($net_price);
        }

        $vat = $this->valueAsNumericValueObject();

        return MathUtils::round(
            $net_price->mul($vat->div(100))->add($net_price)->value(),
            $precision
        );
    }

    public function valueAsNumericValueObject(): ValueObjectNumeric
    {
        return ValueObjectNumeric::create((string) $this->value());
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value): bool
    {
        if ($value < 0) {
            return false;
        }

        if ($value > 100) {
            return false;
        }

        $decimal = MathUtils::numberOfDecimals($value);
        if ($decimal >= 3) {
            return false;
        }

        if (!MathUtils::is_numeric($value)) {
            return false;
        }

        return true;
    }
}
