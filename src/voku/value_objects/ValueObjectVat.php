<?php

declare(strict_types=1);

namespace voku\value_objects;

use voku\value_objects\utils\MathUtils;

/**
 * @extends AbstractValueObject<numeric>
 *
 * @immutable
 */
final class ValueObjectVat extends AbstractValueObject
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

        $vat = $this->getValueObjectNumeric();

        return MathUtils::round(
            $net_price->mul($vat->div(100))->add($net_price)->getValue(),
            $precision
        );
    }

    public function getValueObjectNumeric(): ValueObjectNumeric
    {
        if ($this->value() === null) {
            $value = '0';
        } else {
            $value = (string)$this->value();
        }

        return ValueObjectNumeric::create($value);
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

        if ($value === null) {
            return false;
        }

        $decimal = MathUtils::numberOfDecimals($value);
        if ($decimal >= 3) {
            return false;
        }

        if (!MathUtils::is_numeric($value)) {
            return false;
        }

        return parent::validate($value);
    }
}
