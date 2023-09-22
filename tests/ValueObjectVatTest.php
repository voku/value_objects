<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectVatPercentage;

/**
 * @internal
 */
final class ValueObjectVatTest extends TestCase
{
    public function testSimple(): void
    {
        $vat = ValueObjectVatPercentage::create('16.0');

        static::assertSame('16.0', $vat . '');
        static::assertSame('11.6', $vat->getGross(10.0));
    }
}
