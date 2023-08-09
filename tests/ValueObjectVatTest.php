<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectVat;

/**
 * @internal
 */
final class ValueObjectVatTest extends TestCase
{
    public function testSimple(): void
    {
        $vat = ValueObjectVat::create('16.0');

        static::assertSame('16.0', $vat . '');
        static::assertSame('11.6', $vat->getGross(10.0));
    }
}
